<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign keys to users table (after tenants, partners and manufacturers are created)
        // Nota: Consumidores finais (Customer) não têm usuário no sistema
        Schema::table('users', function (Blueprint $table): void {
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
            $table->foreign('partner_id')->references('id')->on('partners')->nullOnDelete();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->nullOnDelete();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['manufacturer_id']);
            $table->dropForeign(['created_by_user_id']);
        });
    }
};
