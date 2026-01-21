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
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('customer_type', ['PF', 'PJ'])->default('PF');
            $table->string('document', 20)->comment('CPF/CNPJ');
            $table->string('state_registration', 20)->nullable();
            $table->string('name');
            $table->string('trade_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('phone_secondary', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('address_number', 20)->nullable();
            $table->string('address_complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('city_code', 10)->nullable()->comment('Código IBGE');
            $table->char('state', 2)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country', 50)->default('Brasil');
            $table->string('country_code', 10)->default('1058');
            $table->date('birth_date')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('is_from_invoice')->default(false);
            $table->unsignedBigInteger('bling_id')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('document');
            $table->index('name');
            $table->index('phone');
            $table->index('mobile');
            $table->index('postal_code');
        });

        Schema::create('customer_changes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('field_name', 100);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_changes');
        Schema::dropIfExists('customers');
    }
};
