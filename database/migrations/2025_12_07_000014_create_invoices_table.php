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
        // Invoices (Notas Fiscais)
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number', 20);
            $table->string('series', 10)->nullable();
            $table->enum('invoice_type', ['entrada', 'saida'])->default('saida');
            $table->char('purpose', 1)->nullable()->comment('Normal, Complementar, Devolução');
            $table->string('operation_nature');
            $table->char('presence_indicator', 1)->nullable();
            $table->char('destination', 1)->nullable();
            $table->char('final_consumer', 1)->nullable();

            // Emitente
            $table->string('issuer_document', 20);
            $table->string('issuer_name');
            $table->string('issuer_trade_name')->nullable();
            $table->string('issuer_address');
            $table->string('issuer_address_number', 20);
            $table->string('issuer_neighborhood', 100);
            $table->string('issuer_city', 100)->nullable();
            $table->string('issuer_city_code', 10)->nullable();
            $table->char('issuer_state', 2);
            $table->string('issuer_postal_code', 10);
            $table->string('issuer_country', 50)->default('Brasil');
            $table->string('issuer_country_code', 10)->nullable();
            $table->string('issuer_phone', 20)->nullable();
            $table->string('issuer_state_registration', 20)->nullable();
            $table->char('issuer_tax_regime', 1)->nullable();

            // Destinatário
            $table->string('recipient_document', 20);
            $table->string('recipient_name');
            $table->string('recipient_address');
            $table->string('recipient_address_number', 20)->nullable();
            $table->string('recipient_neighborhood', 100);
            $table->string('recipient_city', 100);
            $table->string('recipient_city_code', 10);
            $table->char('recipient_state', 2);
            $table->string('recipient_postal_code', 10)->nullable();
            $table->string('recipient_country', 50)->nullable();
            $table->string('recipient_country_code', 10);
            $table->string('recipient_phone', 20)->nullable();
            $table->string('recipient_state_registration', 20)->nullable();
            $table->string('recipient_ie_indicator', 10)->nullable();

            // Valores e Impostos
            $table->decimal('products_total', 12, 2)->default(0);
            $table->decimal('freight_value', 12, 2)->default(0);
            $table->decimal('insurance_value', 12, 2)->default(0);
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('other_expenses', 12, 2)->default(0);
            $table->decimal('invoice_total', 12, 2)->default(0);
            $table->decimal('icms_base', 12, 2)->default(0);
            $table->decimal('icms_value', 12, 2)->default(0);
            $table->decimal('icms_desonerated', 12, 2)->default(0);
            $table->decimal('icms_fcp', 12, 2)->default(0);
            $table->decimal('icms_st_base', 12, 2)->default(0);
            $table->decimal('icms_st_value', 12, 2)->default(0);
            $table->decimal('icms_fcp_st', 12, 2)->default(0);
            $table->decimal('ipi_value', 12, 2)->default(0);
            $table->decimal('pis_value', 12, 2)->default(0);
            $table->decimal('cofins_value', 12, 2)->default(0);
            $table->decimal('ii_value', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2)->default(0);

            // Controle
            $table->string('invoice_key', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('reason')->nullable();
            $table->text('additional_info')->nullable();
            $table->datetime('issue_date');
            $table->datetime('exit_entry_date');
            $table->datetime('receipt_date')->nullable();
            $table->boolean('is_stock_updated')->default(false);
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            // Referências
            $table->json('referenced_invoices')->nullable();

            $table->timestamps();

            $table->index('invoice_number');
            $table->index('invoice_key');
            $table->index('issuer_document');
            $table->index('recipient_document');
        });

        // Invoice Items
        Schema::create('invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('product_code', 60)->nullable();
            $table->string('ean', 20)->nullable();
            $table->string('product_name');
            $table->string('ncm', 15)->nullable();
            $table->string('cfop', 10)->nullable();
            $table->string('cest', 15)->nullable();
            $table->string('unit', 10)->nullable();
            $table->decimal('quantity', 12, 4)->default(0);
            $table->decimal('unit_price', 12, 4)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);

            // ICMS
            $table->string('icms_origin', 5)->nullable();
            $table->string('icms_cst', 10)->nullable();
            $table->string('icms_base_mode', 10)->nullable();
            $table->decimal('icms_base', 12, 2)->default(0);
            $table->decimal('icms_rate', 5, 2)->default(0);
            $table->decimal('icms_value', 12, 2)->default(0);

            // IPI
            $table->string('ipi_cst', 10)->nullable();
            $table->string('ipi_framework', 10)->nullable();
            $table->decimal('ipi_value', 12, 2)->default(0);

            // PIS
            $table->string('pis_cst', 10)->nullable();
            $table->decimal('pis_base', 12, 2)->default(0);
            $table->decimal('pis_rate', 5, 4)->default(0);
            $table->decimal('pis_value', 12, 2)->default(0);

            // COFINS
            $table->string('cofins_cst', 10)->nullable();
            $table->decimal('cofins_base', 12, 2)->default(0);
            $table->decimal('cofins_rate', 5, 4)->default(0);
            $table->decimal('cofins_value', 12, 2)->default(0);

            $table->timestamps();

            $table->index('invoice_id');
            $table->index('product_code');
        });

        // Invoice Comments (Follow-up para NFs)
        Schema::create('invoice_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('service_order_id')->nullable();
            $table->boolean('is_bound')->default(false);
            $table->string('event')->nullable();
            $table->string('status')->nullable();
            $table->string('colors')->nullable();
            $table->string('icon')->nullable();
            $table->string('part_code', 60)->nullable();
            $table->timestamps();

            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_comments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
