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
        // Integration Providers (Provedores de integração)
        Schema::create('integration_providers', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('type', 50); // nfe_gateway, erp, crm, etc
            $table->string('base_url')->nullable();
            $table->json('default_settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
        });

        // Tenant Integrations (Configuração de integração por tenant)
        Schema::create('tenant_integrations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('integration_provider_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100)->nullable();
            $table->json('credentials')->nullable(); // encrypted in model
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->datetime('last_sync_at')->nullable();
            $table->string('last_sync_status', 50)->nullable();
            $table->text('last_sync_message')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'integration_provider_id']);
        });

        // Integration Logs (Log de operações de integração)
        Schema::create('integration_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_integration_id')->constrained()->cascadeOnDelete();
            $table->string('operation', 100);
            $table->string('direction', 20); // inbound, outbound
            $table->string('entity_type', 100)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('external_id')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->string('status', 50);
            $table->text('error_message')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index('tenant_integration_id');
            $table->index(['entity_type', 'entity_id']);
            $table->index('external_id');
            $table->index('created_at');
        });

        // Webhooks (Webhooks recebidos)
        Schema::create('webhooks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source', 100);
            $table->string('event', 100);
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('status', 50)->default('pending');
            $table->text('processing_result')->nullable();
            $table->datetime('processed_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('source');
            $table->index('event');
            $table->index('status');
            $table->index('created_at');
        });

        // API Tokens (Tokens de API para integrações externas)
        Schema::create('api_tokens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('token', 64)->unique();
            $table->json('abilities')->nullable();
            $table->datetime('last_used_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
        });

        // Scheduled Syncs (Sincronizações agendadas)
        Schema::create('scheduled_syncs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_integration_id')->constrained()->cascadeOnDelete();
            $table->string('sync_type', 100);
            $table->string('cron_expression', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->datetime('last_run_at')->nullable();
            $table->datetime('next_run_at')->nullable();
            $table->string('last_run_status', 50)->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('tenant_integration_id');
            $table->index('next_run_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_syncs');
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('webhooks');
        Schema::dropIfExists('integration_logs');
        Schema::dropIfExists('tenant_integrations');
        Schema::dropIfExists('integration_providers');
    }
};
