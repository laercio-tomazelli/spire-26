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
        // Service Order Parts (Peças da OS)
        Schema::create('service_order_parts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->nullable()->constrained()->nullOnDelete();
            $table->string('part_code', 60);
            $table->string('part_description')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);

            // Classificação
            $table->string('section', 20)->nullable();
            $table->string('defect_code', 50)->nullable();
            $table->string('solution_code', 50)->nullable();
            $table->string('symptom_code')->nullable();
            $table->string('position', 20)->nullable();
            $table->string('type', 20)->nullable();
            $table->enum('request_type', ['normal', 'special'])->default('normal');

            // Status
            $table->string('status', 50)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->text('approval_reason')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('request_reason')->nullable();

            // Pedido
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->datetime('order_date')->nullable();
            $table->string('order_number', 20)->nullable();
            $table->boolean('generates_order')->default(true);

            // NF de Envio
            $table->string('invoice_number', 20)->nullable();
            $table->datetime('invoice_date')->nullable();

            // Logística
            $table->string('eticket', 30)->nullable();
            $table->datetime('sent_at')->nullable();
            $table->string('tracking_code_sent', 30)->nullable();
            $table->date('return_date')->nullable();
            $table->string('tracking_code_return', 30)->nullable();
            $table->text('shipping_observations')->nullable();
            $table->date('received_at_cr_date')->nullable();

            // Recebimento e Aplicação
            $table->string('substitute_part_code', 60)->nullable();
            $table->boolean('is_received')->default(false);
            $table->datetime('received_at')->nullable();
            $table->boolean('is_applied')->default(false);
            $table->datetime('applied_at')->nullable();
            $table->unsignedTinyInteger('shipping_type')->default(0);
            $table->string('partner_part_code', 30)->nullable();

            $table->timestamps();

            $table->index('service_order_id');
            $table->index('part_code');
        });

        // Cost Types
        Schema::create('cost_types', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('product_type')->nullable();
            $table->boolean('is_fixed_cost')->default(true);
            $table->boolean('is_fixed_unit')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->decimal('lg_value', 12, 2)->default(0);
            $table->decimal('tcl_value', 12, 2)->default(0);
            $table->decimal('britania_value', 12, 2)->default(0);
            $table->decimal('efl_value', 12, 2)->default(0);
            $table->decimal('default_value', 12, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Service Order Costs
        Schema::create('service_order_costs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cost_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('unit_count')->default(1);
            $table->decimal('unit_value', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);
            $table->decimal('variable_value', 12, 2)->default(0);
            $table->boolean('is_approved')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->text('observations')->nullable();
            $table->text('validation_observations')->nullable();
            $table->timestamps();

            $table->index('service_order_id');
        });

        // Service Order Comments (Follow-up)
        Schema::create('service_order_comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('username', 100)->nullable();
            $table->text('comment');
            $table->enum('comment_type', ['user', 'system', 'import'])->default('user');
            $table->foreignId('privacy_id')->nullable()->constrained('comment_privacies')->nullOnDelete();
            $table->timestamps();

            $table->index('service_order_id');
        });

        // Service Order Comment Files
        Schema::create('service_order_comment_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_comment_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // Service Order Evidence Files
        Schema::create('service_order_evidence_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evidence_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('uuid', 100)->nullable();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('service_order_id');
            $table->index('uuid');
        });

        // Service Order Technical Support
        Schema::create('service_order_technical_support', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('username', 100)->nullable();
            $table->text('message');
            $table->enum('message_type', ['user', 'system'])->default('user');
            $table->enum('origin', ['partner', 'manufacturer'])->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('ball_with', ['partner', 'manufacturer'])->default('manufacturer');
            $table->foreignId('privacy_id')->nullable()->constrained('comment_privacies')->nullOnDelete();
            $table->timestamps();

            $table->index('service_order_id');
        });

        Schema::create('service_order_technical_support_files', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_order_technical_support_id');
            $table->foreign('service_order_technical_support_id', 'so_tech_support_files_support_id_fk')
                ->references('id')
                ->on('service_order_technical_support')
                ->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // Service Order Admin Support
        Schema::create('service_order_admin_support', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('username', 100)->nullable();
            $table->text('message');
            $table->enum('message_type', ['user', 'system'])->default('user');
            $table->enum('origin', ['partner', 'manufacturer'])->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('ball_with', ['partner', 'manufacturer'])->default('manufacturer');
            $table->foreignId('privacy_id')->nullable()->constrained('comment_privacies')->nullOnDelete();
            $table->timestamps();

            $table->index('service_order_id');
        });

        Schema::create('service_order_admin_support_files', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_order_admin_support_id');
            $table->foreign('service_order_admin_support_id', 'so_admin_support_files_support_id_fk')
                ->references('id')
                ->on('service_order_admin_support')
                ->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // Invite and Schedule Status
        Schema::create('invite_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('schedule_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });

        // Service Order Invites
        Schema::create('service_order_invites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('invite_statuses')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->text('observations')->nullable();
            $table->datetime('responded_at')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->timestamps();

            $table->index('service_order_id');
            $table->index('partner_id');
        });

        // Service Order Schedules
        Schema::create('service_order_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_invite_id')->constrained()->cascadeOnDelete();
            $table->datetime('scheduled_date');
            $table->foreignId('status_id')->nullable()->constrained('schedule_statuses')->nullOnDelete();
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        // Service Order Changes (Audit)
        Schema::create('service_order_changes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('field_name', 100);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();

            $table->index('service_order_id');
        });

        // Service Order Document Downloads (Audit)
        Schema::create('service_order_document_downloads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_name');
            $table->string('document_file');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('downloaded_at');
            $table->timestamps();

            $table->index(['service_order_id', 'downloaded_at'], 'so_doc_downloads_so_id_at_idx');
            $table->index(['user_id', 'downloaded_at'], 'so_doc_downloads_user_id_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_document_downloads');
        Schema::dropIfExists('service_order_changes');
        Schema::dropIfExists('service_order_schedules');
        Schema::dropIfExists('service_order_invites');
        Schema::dropIfExists('schedule_statuses');
        Schema::dropIfExists('invite_statuses');
        Schema::dropIfExists('service_order_admin_support_files');
        Schema::dropIfExists('service_order_admin_support');
        Schema::dropIfExists('service_order_technical_support_files');
        Schema::dropIfExists('service_order_technical_support');
        Schema::dropIfExists('service_order_evidence_files');
        Schema::dropIfExists('service_order_comment_files');
        Schema::dropIfExists('service_order_comments');
        Schema::dropIfExists('service_order_costs');
        Schema::dropIfExists('cost_types');
        Schema::dropIfExists('service_order_parts');
    }
};
