<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();

        // ========================================
        // SPIRE USERS (internal team)
        // ========================================

        // Admin user (Spire)
        $admin = User::create([
            'tenant_id' => $tenant->id,
            'user_type' => 'spire',
            'username' => 'admin',
            'name' => 'Administrador Spire',
            'email' => 'admin@spire.com.br',
            'password' => Hash::make('password'),
            'phone' => '(11) 99999-0001',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        // Manager user (Spire)
        $manager = User::create([
            'tenant_id' => $tenant->id,
            'user_type' => 'spire',
            'username' => 'gerente',
            'name' => 'Gerente Spire',
            'email' => 'gerente@spire.com.br',
            'password' => Hash::make('password'),
            'phone' => '(11) 99999-0002',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $manager->roles()->attach(Role::where('slug', 'manager')->first());

        // Operator user (Spire)
        $operator = User::create([
            'tenant_id' => $tenant->id,
            'user_type' => 'spire',
            'username' => 'operador',
            'name' => 'Operador Spire',
            'email' => 'operador@spire.com.br',
            'password' => Hash::make('password'),
            'phone' => '(11) 99999-0003',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $operator->roles()->attach(Role::where('slug', 'operator')->first());

        // ========================================
        // PARTNER USERS (Postos Autorizados)
        // Partner admin users are created automatically when a Partner is created
        // ========================================

        // Get partners to create admin users for each
        $partners = DB::table('partners')->get();

        foreach ($partners as $partner) {
            // Create Partner Admin User (automatically created when partner is registered)
            $partnerAdmin = User::create([
                'tenant_id' => $tenant->id,
                'partner_id' => $partner->id,
                'user_type' => 'partner',
                'is_partner_admin' => true,
                'username' => $partner->code, // Username = Partner code (e.g., SP001-GBR)
                'name' => 'Admin '.$partner->trade_name,
                'email' => $partner->email ?? strtolower(str_replace('-', '', $partner->code)).'@partner.spire.com.br',
                'password' => Hash::make('password'),
                'phone' => $partner->phone,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $partnerAdmin->roles()->attach(Role::where('slug', 'technician')->first());

            // Create a subordinate technician for the first partner (example)
            if ($partner->id === $partners->first()->id) {
                $partnerTech = User::create([
                    'tenant_id' => $tenant->id,
                    'partner_id' => $partner->id,
                    'user_type' => 'partner',
                    'is_partner_admin' => false,
                    'created_by_user_id' => $partnerAdmin->id,
                    'username' => $partner->code.'-T1',
                    'name' => 'Técnico '.$partner->trade_name,
                    'email' => 'tecnico.'.strtolower(str_replace('-', '', $partner->code)).'@partner.spire.com.br',
                    'password' => Hash::make('password'),
                    'phone' => $partner->phone,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                $partnerTech->roles()->attach(Role::where('slug', 'technician')->first());
            }
        }

        // ========================================
        // MANUFACTURER USERS
        // ========================================

        $manufacturers = DB::table('manufacturers')->get();

        foreach ($manufacturers as $manufacturer) {
            User::create([
                'tenant_id' => $tenant->id,
                'manufacturer_id' => $manufacturer->id,
                'user_type' => 'manufacturer',
                'username' => strtolower(str_replace(' ', '', $manufacturer->name)),
                'name' => 'Representante '.$manufacturer->name,
                'email' => 'contato@'.strtolower(str_replace(' ', '', $manufacturer->name)).'.com.br',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // ========================================
        // CLIENT USERS (End Customers - optional)
        // ========================================

        // Create one example client user
        $customer = DB::table('customers')->first();

        if ($customer) {
            User::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'user_type' => 'client',
                'username' => null, // Clients typically use email to login
                'name' => $customer->name,
                'email' => $customer->email ?? 'cliente@exemplo.com.br',
                'password' => Hash::make('password'),
                'phone' => $customer->phone,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
