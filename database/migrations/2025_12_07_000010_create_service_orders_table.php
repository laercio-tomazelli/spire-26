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
        Schema::create('service_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('order_number')->comment('Número sequencial por tenant');
            $table->string('protocol', 50)->nullable();

            // Referências Externas
            $table->string('manufacturer_pre_order', 50)->nullable();
            $table->datetime('manufacturer_pre_order_date')->nullable();
            $table->string('manufacturer_order', 100)->nullable();
            $table->datetime('manufacturer_order_date')->nullable();
            $table->string('partner_order', 100)->nullable();
            $table->datetime('partner_order_date')->nullable();
            $table->string('external_id', 100)->nullable()->comment('ID sistema externo TPV');

            // Relacionamentos
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_model_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();

            // Dados do Produto
            $table->string('model_received', 100)->nullable()->comment('Modelo informado');
            $table->string('serial_number')->nullable();

            // Dados da Compra
            $table->string('retailer_name')->nullable();
            $table->string('purchase_invoice_number')->nullable();
            $table->date('purchase_invoice_date')->nullable();
            $table->decimal('purchase_value', 12, 2)->default(0);
            $table->string('purchase_invoice_file')->nullable();

            // Classificação
            $table->foreignId('service_location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_order_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('repair_type_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('warranty_type', ['in_warranty', 'out_of_warranty'])->nullable();

            // Status
            $table->foreignId('status_id')->nullable()->constrained('service_order_statuses')->nullOnDelete();
            $table->foreignId('tracking_status_id')->nullable()->constrained('tracking_statuses')->nullOnDelete();
            $table->foreignId('accept_status_id')->nullable()->constrained('accept_statuses')->nullOnDelete();
            $table->string('manufacturer_status')->nullable();

            // Defeito e Reparo
            $table->text('reported_defect')->nullable();
            $table->text('confirmed_defect')->nullable();
            $table->char('defect_condition', 1)->nullable();
            $table->string('symptom', 100)->nullable();
            $table->string('repair_description')->nullable();
            $table->string('accessories')->nullable();
            $table->string('conditions')->nullable();
            $table->text('observations')->nullable();

            // Flags
            $table->boolean('is_reentry')->default(false);
            $table->unsignedBigInteger('reentry_order_id')->nullable();
            $table->boolean('is_critical')->default(false);
            $table->boolean('is_no_defect')->default(false);
            $table->boolean('has_parts_used')->default(false);
            $table->boolean('is_display')->default(false);

            // Troca/Devolução
            $table->boolean('is_exchange')->default(false);
            $table->enum('exchange_type', ['product', 'refund'])->nullable();
            $table->text('exchange_reason')->nullable();
            $table->unsignedBigInteger('exchange_model_id')->nullable();
            $table->decimal('exchange_negotiated_value', 12, 2)->default(0);
            $table->datetime('exchange_analysis_date')->nullable();
            $table->datetime('exchange_approval_date')->nullable();
            $table->unsignedBigInteger('exchange_analyzed_by')->nullable();
            $table->char('exchange_result', 1)->nullable();

            // Custos Adicionais
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->unsignedInteger('distance_km')->nullable();
            $table->decimal('km_cost', 12, 2)->default(0);
            $table->decimal('extra_cost', 12, 2)->default(0);
            $table->unsignedTinyInteger('visit_count')->nullable();

            // Datas do Fluxo
            $table->datetime('opened_at')->nullable();
            $table->unsignedBigInteger('opened_by')->nullable();
            $table->datetime('evaluated_at')->nullable();
            $table->unsignedBigInteger('evaluated_by')->nullable();
            $table->datetime('repaired_at')->nullable();
            $table->unsignedBigInteger('repaired_by')->nullable();
            $table->datetime('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->datetime('manufacturer_closed_at')->nullable();
            $table->datetime('manufacturer_approved_at')->nullable();
            $table->unsignedBigInteger('manufacturer_approved_by')->nullable();

            // Aceite/Rejeição do Posto
            $table->datetime('accepted_at')->nullable();
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->text('rejection_reason')->nullable();

            // Logística de Entrada
            $table->string('entry_invoice_number', 50)->nullable();
            $table->datetime('entry_invoice_date')->nullable();
            $table->string('entry_tracking_code', 50)->nullable();
            $table->datetime('received_at')->nullable();
            $table->string('received_serial')->nullable();

            // Logística de Saída
            $table->string('exit_invoice_number', 50)->nullable();
            $table->datetime('exit_invoice_date')->nullable();
            $table->string('exit_tracking_code', 50)->nullable();
            $table->datetime('exit_sent_at')->nullable();
            $table->datetime('delivered_at')->nullable();

            // Coleta (para domicílio)
            $table->string('collection_invoice_number', 100)->nullable();
            $table->datetime('collection_invoice_date')->nullable();
            $table->string('collection_number', 100)->nullable();
            $table->datetime('collection_date')->nullable();
            $table->datetime('scheduled_visit_date')->nullable();

            // Controle
            $table->foreignId('closing_type_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_admin_closed')->default(false);
            $table->text('process_observations')->nullable();

            // OS Relacionadas
            $table->unsignedBigInteger('parent_order_id')->nullable()->comment('OS pai');
            $table->unsignedBigInteger('exchange_origin_order_id')->nullable();
            $table->unsignedBigInteger('exchange_order_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique(['tenant_id', 'order_number']);
            $table->index('protocol');
            $table->index('manufacturer_order');
            $table->index('partner_order');
            $table->index('status_id');
            $table->index('customer_id');
            $table->index('partner_id');
            $table->index('brand_id');
            $table->index('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
