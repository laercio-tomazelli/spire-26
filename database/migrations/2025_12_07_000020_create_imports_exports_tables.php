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
        // Import Batches (Lotes de importação)
        Schema::create('import_batches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 100); // customers, products, service_orders, etc
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('success_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);
            $table->unsignedInteger('skip_count')->default(0);
            $table->string('status', 50)->default('pending');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->json('settings')->nullable();
            $table->json('column_mapping')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('type');
            $table->index('status');
        });

        // Import Rows (Linhas individuais da importação)
        Schema::create('import_rows', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('import_batch_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->json('original_data');
            $table->json('processed_data')->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('error_message')->nullable();
            $table->json('validation_errors')->nullable();
            $table->string('created_entity_type')->nullable();
            $table->unsignedBigInteger('created_entity_id')->nullable();
            $table->timestamps();

            $table->index('import_batch_id');
            $table->index('status');
        });

        // Export Batches (Lotes de exportação)
        Schema::create('export_batches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 100);
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_format', 20)->default('csv');
            $table->unsignedInteger('total_rows')->default(0);
            $table->string('status', 50)->default('pending');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->json('filters')->nullable();
            $table->json('columns')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('type');
            $table->index('status');
        });

        // Report Templates (Templates de relatórios)
        Schema::create('report_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->string('type', 50);
            $table->json('parameters')->nullable();
            $table->json('columns')->nullable();
            $table->longText('query')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('type');
        });

        // Generated Reports (Relatórios gerados)
        Schema::create('generated_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('report_template_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->string('file_format', 20)->default('pdf');
            $table->json('parameters')->nullable();
            $table->string('status', 50)->default('pending');
            $table->datetime('generated_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_reports');
        Schema::dropIfExists('report_templates');
        Schema::dropIfExists('export_batches');
        Schema::dropIfExists('import_rows');
        Schema::dropIfExists('import_batches');
    }
};
