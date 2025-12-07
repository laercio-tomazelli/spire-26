<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Partner;
use App\Models\Tenant;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $brands = Brand::all();

        // Create main warehouse
        Warehouse::create([
            'tenant_id' => $tenant->id,
            'name' => 'Estoque Central',
            'code' => 'WH-001',
            'type' => 'main',
            'location' => 'São Paulo, SP',
            'description' => 'Depósito principal da empresa',
        ]);

        // Create some partners
        $partners = [
            [
                'code' => 'SP001-ELE',
                'document' => '12.345.678/0001-90',
                'company_name' => 'TechService Assistência Técnica Ltda',
                'trade_name' => 'TechService',
                'email' => 'contato@techservice.com.br',
                'phone' => '(11) 3333-4444',
                'address' => 'Rua das Palmeiras',
                'address_number' => '500',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01310-100',
            ],
            [
                'code' => 'RJ001-ELE',
                'document' => '98.765.432/0001-10',
                'company_name' => 'Reparos Express EIRELI',
                'trade_name' => 'Reparos Express',
                'email' => 'contato@reparosexpress.com.br',
                'phone' => '(21) 2222-3333',
                'address' => 'Av. Rio Branco',
                'address_number' => '100',
                'neighborhood' => 'Centro',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'postal_code' => '20040-020',
            ],
            [
                'code' => 'MG001-ELE',
                'document' => '11.222.333/0001-44',
                'company_name' => 'Consertos Rápidos ME',
                'trade_name' => 'Consertos Rápidos',
                'email' => 'contato@consertosrapidos.com.br',
                'phone' => '(31) 3333-2222',
                'address' => 'Rua da Bahia',
                'address_number' => '200',
                'neighborhood' => 'Savassi',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'postal_code' => '30130-000',
            ],
        ];

        foreach ($partners as $data) {
            $partner = Partner::create([
                'tenant_id' => $tenant->id,
                'code' => $data['code'],
                'document_type' => 'CNPJ',
                'document' => $data['document'],
                'company_name' => $data['company_name'],
                'trade_name' => $data['trade_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'address_number' => $data['address_number'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'state' => $data['state'],
                'postal_code' => $data['postal_code'],
                'person_type' => 'PJ',
                'status' => 'active',
            ]);

            // Create partner warehouse
            Warehouse::create([
                'tenant_id' => $tenant->id,
                'name' => 'Estoque '.$data['trade_name'],
                'code' => 'WH-'.$data['code'],
                'type' => 'partner',
                'location' => $data['city'].', '.$data['state'],
                'partner_id' => $partner->id,
            ]);

            // Associate with brands (using pivot table directly)
            foreach ($brands->take(3) as $brand) {
                DB::table('partner_brands')->insert([
                    'partner_id' => $partner->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Associate with product lines
            $productLines = DB::table('product_lines')->get();
            foreach ($productLines as $productLine) {
                DB::table('partner_product_lines')->insert([
                    'partner_id' => $partner->id,
                    'product_line_id' => $productLine->id,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Associate with product categories
            $productCategories = DB::table('product_categories')->get();
            foreach ($productCategories as $category) {
                DB::table('partner_product_categories')->insert([
                    'partner_id' => $partner->id,
                    'product_category_id' => $category->id,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
