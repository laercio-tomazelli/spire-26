<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'name' => 'SPIRE Demo',
            'trade_name' => 'SPIRE Sistema',
            'document' => '12.345.678/0001-90',
            'email' => 'contato@spire-demo.com.br',
            'phone' => '(11) 3000-0000',
            'is_active' => true,
            'settings' => [
                'timezone' => 'America/Sao_Paulo',
                'locale' => 'pt_BR',
                'currency' => 'BRL',
            ],
        ]);
    }
}
