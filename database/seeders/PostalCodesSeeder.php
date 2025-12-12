<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostalCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Imports postal codes from legacy database spire_prod_new_03_12
     */
    public function run(): void
    {
        $legacyConnection = 'legacy';

        // Check if legacy connection is configured
        if (! config('database.connections.'.$legacyConnection)) {
            $this->command->error('Legacy database connection not configured. Add "legacy" connection to config/database.php');

            return;
        }

        $this->command->info('Starting postal codes import from legacy database...');

        $batchSize = 5000;
        $totalImported = 0;

        // Get total count
        $total = DB::connection($legacyConnection)
            ->table('ceps')
            ->count();

        $this->command->info("Total records to import: {$total}");

        // Truncate existing data
        DB::table('postal_codes')->truncate();

        // Import in batches
        DB::connection($legacyConnection)
            ->table('ceps')
            ->orderBy('Codigo')
            ->chunk($batchSize, function ($records) use (&$totalImported, $total): void {
                $data = [];

                foreach ($records as $record) {
                    $data[] = [
                        'code' => ltrim((string) $record->cep, '0'),
                        'code_range' => $record->cep_faixa ? ltrim((string) $record->cep_faixa, '0') : null,
                        'state' => $record->uf,
                        'city' => $record->cidade,
                        'street' => $record->endereco,
                        'complement' => $record->complemento,
                        'neighborhood' => $record->bairro,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('postal_codes')->insert($data);

                $totalImported += count($records);
                $percentage = round(($totalImported / $total) * 100, 1);
                $this->command->info("Imported {$totalImported} / {$total} ({$percentage}%)");
            });

        $this->command->info("Postal codes import completed! Total: {$totalImported} records.");
    }
}
