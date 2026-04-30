<?php

declare(strict_types=1);

namespace App\Console\Commands;

use ZipArchive;
use SimpleXMLElement;
use Illuminate\Support\Facades\Date;
use Throwable;
use App\Enums\InspectionStatus;
use App\Models\Inspection;
use App\Models\Tenant;
use Illuminate\Console\Command;

class ImportCardifInspections extends Command
{
    protected $signature = 'cardif:import-inspections
        {file : Path to the .xlsx file}
        {--tenant=Cardif : Tenant name}
        {--sheet=Base - Cardif : Sheet name to import}';

    protected $description = 'Importa vistorias da planilha Consolidado - CARDIF.xlsx (aba Base - Cardif).';

    public function handle(): int
    {
        $file = (string) $this->argument('file');

        if (! is_file($file)) {
            $this->error("Arquivo não encontrado: {$file}");

            return self::FAILURE;
        }

        $tenant = Tenant::query()->where('name', $this->option('tenant'))->first();
        if (! $tenant) {
            $this->error("Tenant '{$this->option('tenant')}' não encontrado.");

            return self::FAILURE;
        }

        $rows = $this->readSheet($file, (string) $this->option('sheet'));
        if ($rows === []) {
            $this->error('Nenhuma linha lida da planilha.');

            return self::FAILURE;
        }

        $headers = array_map(
            fn (?string $v): string => mb_strtoupper(trim((string) $v)),
            array_shift($rows),
        );

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $data = array_combine(
                array_slice($headers, 0, count($row)),
                array_pad($row, count($headers), null),
            );

            $claim = trim((string) ($data['SINISTRO'] ?? ''));
            if ($claim === '') {
                $skipped++;
                continue;
            }

            $payload = [
                'tenant_id' => $tenant->id,
                'reference_date' => $this->excelToDate($data['DATA'] ?? null),
                'tat_days' => $this->toInt($data['TAT'] ?? null),
                'customer_name' => trim((string) ($data['CLIENTE'] ?? '—')) ?: '—',
                'customer_contact' => $this->trimOrNull($data['CONTATOS'] ?? null),
                'product' => $this->trimOrNull($data['PRODUTO'] ?? null),
                'reports_count' => $this->toInt($data['Nº LAUDOS'] ?? $data['N° LAUDOS'] ?? 1) ?? 1,
                'contact_date' => $this->excelToDate($data['DATA DO CONTATO'] ?? null),
                'inspection_date' => $this->excelToDate($data['DATA DA VISTORIA'] ?? null),
                'inspection_time' => $this->excelToTime($data['HORA'] ?? null),
                'report_sent_at' => $this->excelToDate($data['DATA DE ENVIO LAUDO'] ?? null),
                'status' => InspectionStatus::fromLabel((string) ($data['STATUS'] ?? ''))->value,
                'has_electrical_damage' => $this->toBool($data['POSSUI DANO ELÉTRICO'] ?? null),
                'remark' => $this->trimOrNull($data['REMARK'] ?? null),
            ];

            $existing = Inspection::withoutGlobalScope('tenant')
                ->where('tenant_id', $tenant->id)
                ->where('claim_number', $claim)
                ->first();

            if ($existing) {
                $existing->fill($payload)->save();
                $updated++;
            } else {
                Inspection::create($payload + ['claim_number' => $claim]);
                $created++;
            }
        }

        $this->info("Concluído. Criados: {$created} | Atualizados: {$updated} | Ignorados: {$skipped}");

        return self::SUCCESS;
    }

    /**
     * @return list<list<string|null>>
     */
    private function readSheet(string $file, string $sheetName): array
    {
        $zip = new ZipArchive;
        if ($zip->open($file) !== true) {
            return [];
        }

        // Shared strings
        $shared = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if (is_string($ssXml)) {
            $ss = simplexml_load_string($ssXml);
            if ($ss !== false) {
                foreach ($ss->si as $si) {
                    $shared[] = ($si->t ?? '').implode('', array_map(
                        fn (SimpleXMLElement $r): string => (string) ($r->t ?? ''),
                        iterator_to_array($si->r ?? []),
                    ));
                }
            }
        }

        // Workbook to find sheet target
        $wbXml = $zip->getFromName('xl/workbook.xml');
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if (! is_string($wbXml) || ! is_string($relsXml)) {
            $zip->close();

            return [];
        }

        $wb = simplexml_load_string($wbXml);
        $rels = simplexml_load_string($relsXml);

        $relMap = [];
        foreach ($rels->Relationship as $rel) {
            $relMap[(string) $rel['Id']] = (string) $rel['Target'];
        }

        $target = null;
        foreach ($wb->sheets->sheet as $s) {
            if ((string) $s['name'] === $sheetName) {
                $rid = (string) $s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')->id;
                $target = $relMap[$rid] ?? null;
                break;
            }
        }

        if ($target === null) {
            $zip->close();

            return [];
        }

        $sheetXml = $zip->getFromName('xl/'.$target);
        $zip->close();

        if (! is_string($sheetXml)) {
            return [];
        }

        $sheet = simplexml_load_string($sheetXml);
        $rows = [];

        foreach ($sheet->sheetData->row as $row) {
            $cells = [];
            $maxIdx = -1;
            foreach ($row->c as $c) {
                $ref = (string) $c['r'];
                preg_match('/([A-Z]+)/', $ref, $m);
                $colIdx = 0;
                foreach (str_split($m[1]) as $ch) {
                    $colIdx = $colIdx * 26 + (ord($ch) - 64);
                }
                $colIdx--;
                $maxIdx = max($maxIdx, $colIdx);

                $type = (string) $c['t'];
                $value = null;

                if ($type === 's') {
                    $value = $shared[(int) $c->v] ?? null;
                } elseif ($type === 'inlineStr') {
                    $value = (string) ($c->is->t ?? '');
                } elseif (property_exists($c, 'v') && $c->v !== null) {
                    $value = (string) $c->v;
                }

                $cells[$colIdx] = $value;
            }

            if ($maxIdx === -1) {
                continue;
            }

            $rowArr = [];
            for ($i = 0; $i <= $maxIdx; $i++) {
                $rowArr[] = $cells[$i] ?? null;
            }
            $rows[] = $rowArr;
        }

        return $rows;
    }

    private function excelToDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            // Excel epoch (1900-based)
            $unix = ((int) $value - 25569) * 86400;

            return Date::createFromTimestampUTC($unix)->format('Y-m-d');
        }

        try {
            return Date::parse((string) $value)->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }

    private function excelToTime(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $fraction = (float) $value - (int) $value;
            $seconds = (int) round($fraction * 86400);
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);

            return sprintf('%02d:%02d:00', $h, $m);
        }

        try {
            return Date::parse((string) $value)->format('H:i:s');
        } catch (Throwable) {
            return null;
        }
    }

    private function toInt(mixed $v): ?int
    {
        if ($v === null || $v === '') {
            return null;
        }

        return (int) $v;
    }

    private function toBool(mixed $v): bool
    {
        $s = mb_strtoupper(trim((string) $v));

        return in_array($s, ['SIM', 'S', 'YES', 'Y', '1', 'TRUE'], true);
    }

    private function trimOrNull(mixed $v): ?string
    {
        $s = trim((string) $v);

        return $s === '' ? null : $s;
    }
}
