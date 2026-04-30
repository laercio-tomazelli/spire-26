<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CardifTenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::firstOrCreate(
            ['document' => '08.279.191/0001-84'],
            [
                'name' => 'Cardif',
                'trade_name' => 'Cardif do Brasil Seguros e Garantias S.A.',
                'email' => 'contato@cardif.com.br',
                'phone' => '(11) 4002-8922',
                'is_active' => true,
                'settings' => [
                    'timezone' => 'America/Sao_Paulo',
                    'locale' => 'pt_BR',
                    'currency' => 'BRL',
                    'theme' => [
                        'primary' => '#0050B3',
                        'accent' => '#13C2C2',
                    ],
                ],
            ],
        );
    }
}
