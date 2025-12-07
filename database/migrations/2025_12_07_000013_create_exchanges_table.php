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
        // Exchange Reasons
        Schema::create('exchange_reasons', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();

            $table->unique('code');
        });

        // Exchange Statuses
        Schema::create('exchange_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50);
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });

        // Exchanges
        Schema::create('exchanges', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('uuid', 100)->nullable();

            // Tipo
            $table->enum('exchange_type', ['via_partner', 'direct_consumer'])->default('direct_consumer');

            // Relacionamentos
            $table->foreignId('service_order_id')->nullable()->constrained()->nullOnDelete()->comment('OS origem');
            $table->unsignedBigInteger('exchange_service_order_id')->nullable()->comment('OS troca');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();

            // Produto Original
            $table->foreignId('original_model_id')->nullable()->constrained('product_models')->nullOnDelete();
            $table->string('original_model_name', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('retailer_name', 200)->nullable();
            $table->string('purchase_invoice_number', 50)->nullable();
            $table->date('purchase_invoice_date')->nullable();
            $table->decimal('purchase_value', 12, 2)->nullable();

            // Defeito/Condições
            $table->text('reported_defect')->nullable();
            $table->text('product_conditions')->nullable();

            // Decisão de Troca
            $table->enum('exchange_decision', ['product', 'refund'])->nullable();
            $table->decimal('negotiated_value', 12, 2)->nullable();
            $table->foreignId('exchange_model_id')->nullable()->constrained('product_models')->nullOnDelete();
            $table->string('exchange_model_name', 100)->nullable();
            $table->foreignId('exchange_reason_id')->nullable()->constrained()->nullOnDelete();
            $table->text('exchange_reason_text')->nullable();

            // Status
            $table->foreignId('status_id')->nullable()->constrained('exchange_statuses')->nullOnDelete();

            // Evidências (paths)
            $table->string('invoice_evidence_path')->nullable();
            $table->string('label_evidence_path')->nullable();
            $table->string('defect_evidence_path')->nullable();

            // Solicitação
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->timestamp('requested_at')->nullable();

            // Aprovação
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index('service_order_id');
            $table->index('customer_id');
            $table->index('partner_id');
            $table->index('status_id');
        });

        // Exchange Comments
        Schema::create('exchange_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exchange_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('username', 100)->nullable();
            $table->text('comment');
            $table->enum('comment_type', ['user', 'system'])->default('user');
            $table->foreignId('privacy_id')->nullable()->constrained('comment_privacies')->nullOnDelete();
            $table->timestamps();

            $table->index('exchange_id');
        });

        // Exchange Comment Files
        Schema::create('exchange_comment_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exchange_comment_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // Exchange Evidence Files
        Schema::create('exchange_evidence_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('exchange_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evidence_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('uuid', 100)->nullable();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('exchange_id');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_evidence_files');
        Schema::dropIfExists('exchange_comment_files');
        Schema::dropIfExists('exchange_comments');
        Schema::dropIfExists('exchanges');
        Schema::dropIfExists('exchange_statuses');
        Schema::dropIfExists('exchange_reasons');
    }
};
