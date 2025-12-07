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
        // Lookup tables para Partners
        Schema::create('company_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('tax_regimes', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('pix_key_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 50);
            $table->timestamps();
        });

        Schema::create('contact_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 50);
            $table->timestamps();
        });

        // Partners (Postos Autorizados)
        Schema::create('partners', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20)->comment('Código único ex: SP002-GBR');
            $table->enum('document_type', ['CPF', 'CNPJ'])->default('CNPJ');
            $table->string('document', 20);
            $table->string('state_registration', 20)->nullable()->comment('IE');
            $table->boolean('is_tax_exempt')->default(false);
            $table->string('company_name');
            $table->string('trade_name')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('email_secondary', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_secondary', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->string('address');
            $table->string('address_number', 20)->nullable();
            $table->string('address_complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100);
            $table->char('state', 2);
            $table->string('postal_code', 10);
            $table->foreignId('company_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tax_regime_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('person_type', ['PF', 'PJ'])->default('PJ');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->char('level', 1)->nullable()->comment('A, B, C');
            $table->string('category', 100)->nullable();
            $table->string('bank_code', 10)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_agency', 20)->nullable();
            $table->string('bank_account', 30)->nullable();
            $table->string('pix_key')->nullable();
            $table->foreignId('pix_key_type_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('account_type', ['corrente', 'poupanca'])->nullable();
            $table->text('bank_observations')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedBigInteger('bling_id')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->unique(['tenant_id', 'document']);
            $table->index('code');
            $table->index('document');
            $table->index('status');
        });

        // Pivot: Partner x Brands
        Schema::create('partner_brands', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['partner_id', 'brand_id']);
        });

        // Pivot: Partner x Product Lines
        Schema::create('partner_product_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_line_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['partner_id', 'product_line_id']);
        });

        // Pivot: Partner x Product Categories
        Schema::create('partner_product_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['partner_id', 'product_category_id'], 'partner_category_unique');
        });

        // Partner Contacts
        Schema::create('partner_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('partner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_contacts');
        Schema::dropIfExists('partner_product_categories');
        Schema::dropIfExists('partner_product_lines');
        Schema::dropIfExists('partner_brands');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('contact_types');
        Schema::dropIfExists('pix_key_types');
        Schema::dropIfExists('tax_regimes');
        Schema::dropIfExists('company_types');
    }
};
