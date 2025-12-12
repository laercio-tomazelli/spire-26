<?php

declare(strict_types=1);

/**
 * Script to import postal codes from legacy database
 * Run this script locally (not in Docker) to avoid connection issues
 *
 * Usage: php scripts/import-postal-codes.php
 */
$legacyHost = '127.0.0.1';
$legacyPort = 3306;
$legacyDb = 'spire_prod_new_03_12';
$legacyUser = 'laercio';
$legacyPass = 'caluma';

$targetHost = '127.0.0.1';
$targetPort = 3366;
$targetDb = 'spire';
$targetUser = 'spire';
$targetPass = 'secret';

echo "Connecting to legacy database...\n";

try {
    $legacy = new PDO(
        "mysql:host={$legacyHost};port={$legacyPort};dbname={$legacyDb};charset=utf8mb4",
        $legacyUser,
        $legacyPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    );
} catch (PDOException $e) {
    exit('Legacy DB connection failed: '.$e->getMessage()."\n");
}

echo "Connecting to target database...\n";

try {
    $target = new PDO(
        "mysql:host={$targetHost};port={$targetPort};dbname={$targetDb};charset=utf8mb4",
        $targetUser,
        $targetPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    );
} catch (PDOException $e) {
    exit('Target DB connection failed: '.$e->getMessage()."\n");
}

echo "Getting total count...\n";
$total = $legacy->query('SELECT COUNT(*) FROM ceps')->fetchColumn();
echo "Total records to import: {$total}\n";

echo "Truncating postal_codes table...\n";
$target->exec('TRUNCATE TABLE postal_codes');

$batchSize = 5000;
$offset = 0;
$imported = 0;

$insertSql = 'INSERT INTO postal_codes (code, code_range, state, city, street, complement, neighborhood, created_at, updated_at) VALUES ';

$now = date('Y-m-d H:i:s');

echo "Starting import...\n";

while ($offset < $total) {
    $stmt = $legacy->prepare('SELECT cep, cep_faixa, uf, cidade, endereco, complemento, bairro FROM ceps ORDER BY Codigo LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $batchSize, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        break;
    }

    $values = [];
    foreach ($rows as $row) {
        $code = ltrim($row['cep'] ?? '', '0') ?: '0';
        $codeRange = $row['cep_faixa'] ? (ltrim($row['cep_faixa'], '0') ?: '0') : null;
        $state = $row['uf'];
        $city = $row['cidade'];
        $street = $row['endereco'];
        $complement = $row['complemento'];
        $neighborhood = $row['bairro'];

        $values[] = sprintf(
            '(%s, %s, %s, %s, %s, %s, %s, %s, %s)',
            $target->quote($code),
            $codeRange ? $target->quote($codeRange) : 'NULL',
            $state ? $target->quote($state) : 'NULL',
            $city ? $target->quote($city) : 'NULL',
            $street ? $target->quote($street) : 'NULL',
            $complement ? $target->quote($complement) : 'NULL',
            $neighborhood ? $target->quote($neighborhood) : 'NULL',
            $target->quote($now),
            $target->quote($now),
        );
    }

    $target->exec($insertSql.implode(',', $values));

    $imported += count($rows);
    $offset += $batchSize;
    $percentage = round(($imported / $total) * 100, 1);
    echo "\rImported {$imported} / {$total} ({$percentage}%)";
}

echo "\n\nImport completed! Total: {$imported} records.\n";
