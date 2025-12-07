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
        // Order Statuses
        Schema::create('order_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->string('color', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->char('alias', 2)->nullable();
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('order_number');
            $table->foreignId('service_order_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('exchange_id')->nullable();
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            // Classificação
            $table->enum('order_type', ['parts', 'exchange', 'buffer'])->default('parts');
            $table->string('service_order_type', 10)->nullable();

            // Status
            $table->foreignId('status_id')->nullable()->constrained('order_statuses')->nullOnDelete();
            $table->char('billing_status', 1)->nullable();
            $table->string('gateway_status')->nullable();

            // Valores
            $table->unsignedSmallInteger('total_items')->default(0);
            $table->decimal('total_value', 12, 2)->default(0);

            // Integração Gateway/Bling
            $table->unsignedBigInteger('gateway_order_id')->nullable();
            $table->datetime('gateway_order_date')->nullable();
            $table->unsignedBigInteger('gateway_input_order_id')->nullable();
            $table->datetime('gateway_input_order_date')->nullable();
            $table->unsignedBigInteger('bling_order_id')->nullable();
            $table->datetime('bling_order_date')->nullable();

            // Datas do Fluxo
            $table->datetime('order_date')->nullable();
            $table->datetime('verified_at')->nullable();
            $table->datetime('separated_at')->nullable();
            $table->datetime('collected_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->datetime('estimated_delivery_date')->nullable();

            // Aprovação
            $table->boolean('is_approved')->default(false);
            $table->datetime('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            // Cancelamento
            $table->datetime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->text('observations')->nullable();
            $table->string('uid')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'order_number']);
            $table->index('service_order_id');
            $table->index('exchange_id');
            $table->index('status_id');
        });

        // Order Items
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('service_order_part_id')->nullable();
            $table->string('part_code', 60);
            $table->string('substitute_part_code', 60)->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('icms_value', 12, 2)->default(0);
            $table->decimal('ipi_value', 12, 2)->default(0);
            $table->decimal('st_value', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);

            // NF
            $table->string('invoice_number', 20)->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('manufacturer_invoice_number', 50)->nullable();
            $table->datetime('manufacturer_invoice_date')->nullable();
            $table->unsignedBigInteger('invoice_binding_id')->nullable();
            $table->boolean('is_invoice_ok')->default(false);

            // Status
            $table->char('billing_status', 1)->nullable();
            $table->boolean('is_reserved')->default(false);
            $table->boolean('is_blocked')->default(true);
            $table->boolean('is_approved')->default(false);

            // Datas do Fluxo
            $table->datetime('verified_at')->nullable();
            $table->string('verified_by')->nullable();
            $table->datetime('separated_at')->nullable();
            $table->string('separated_by')->nullable();
            $table->datetime('collected_at')->nullable();

            $table->text('observations')->nullable();
            $table->string('uid')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('part_code');
        });

        // Order Comments (Follow-up)
        Schema::create('order_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('comment');
            $table->enum('comment_type', ['user', 'system'])->default('user');
            $table->timestamps();

            $table->index('order_id');
        });

        // Order Invoices
        Schema::create('order_invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_order_number', 30)->nullable();
            $table->string('order_number', 50)->nullable();
            $table->string('invoice_number', 30)->nullable();
            $table->datetime('invoice_date')->nullable();
            $table->string('invoice_file', 100)->nullable();
            $table->string('cfop')->nullable();
            $table->string('product_code', 60)->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('value', 12, 2)->default(0);
            $table->text('additional_info')->nullable();
            $table->string('invoice_key')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_invoices');
        Schema::dropIfExists('order_comments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_statuses');
    }
};
