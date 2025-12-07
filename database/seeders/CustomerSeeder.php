<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        $customers = [
            [
                'customer_type' => 'PF',
                'document' => '123.456.789-00',
                'name' => 'João da Silva',
                'phone' => '(11) 98765-4321',
                'email' => 'joao.silva@example.com',
                'postal_code' => '01310-100',
                'state' => 'SP',
                'city' => 'São Paulo',
                'neighborhood' => 'Bela Vista',
                'address' => 'Av. Paulista',
                'address_number' => '1000',
                'address_complement' => 'Apto 101',
            ],
            [
                'customer_type' => 'PF',
                'document' => '987.654.321-00',
                'name' => 'Maria Oliveira',
                'phone' => '(21) 99876-5432',
                'email' => 'maria.oliveira@example.com',
                'postal_code' => '20040-020',
                'state' => 'RJ',
                'city' => 'Rio de Janeiro',
                'neighborhood' => 'Centro',
                'address' => 'Rua do Ouvidor',
                'address_number' => '50',
            ],
            [
                'customer_type' => 'PJ',
                'document' => '12.345.678/0001-90',
                'name' => 'Empresa ABC Ltda',
                'trade_name' => 'ABC Comércio',
                'phone' => '(31) 3333-4444',
                'email' => 'contato@abcltda.com.br',
                'postal_code' => '30130-000',
                'state' => 'MG',
                'city' => 'Belo Horizonte',
                'neighborhood' => 'Savassi',
                'address' => 'Rua Pernambuco',
                'address_number' => '200',
            ],
            [
                'customer_type' => 'PF',
                'document' => '111.222.333-44',
                'name' => 'Carlos Santos',
                'phone' => '(41) 98888-7777',
                'email' => 'carlos.santos@example.com',
                'postal_code' => '80010-000',
                'state' => 'PR',
                'city' => 'Curitiba',
                'neighborhood' => 'Centro',
                'address' => 'Rua XV de Novembro',
                'address_number' => '300',
            ],
            [
                'customer_type' => 'PF',
                'document' => '555.666.777-88',
                'name' => 'Ana Pereira',
                'phone' => '(51) 97777-6666',
                'email' => 'ana.pereira@example.com',
                'postal_code' => '90010-000',
                'state' => 'RS',
                'city' => 'Porto Alegre',
                'neighborhood' => 'Moinhos de Vento',
                'address' => 'Rua Padre Chagas',
                'address_number' => '150',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create([
                'tenant_id' => $tenant->id,
                ...$customerData,
            ]);
        }
    }
}
