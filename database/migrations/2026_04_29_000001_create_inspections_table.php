<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete()->comment('Tenant owner (Cardif)');

            // Identificação (planilha)
            $table->string('claim_number', 30)->index()->comment('SINISTRO');
            $table->date('reference_date')->nullable()->comment('DATA');
            $table->unsignedSmallInteger('tat_days')->nullable()->comment('TAT - Turn Around Time em dias');

            // Cliente
            $table->string('customer_name')->comment('CLIENTE');
            $table->string('customer_contact', 50)->nullable()->comment('CONTATOS');

            // Produto
            $table->string('product', 100)->nullable()->comment('PRODUTO (REFRIGERADOR, PC, etc.)');
            $table->boolean('has_electrical_damage')->default(false)->comment('POSSUI DANO ELÉTRICO');

            // Laudos
            $table->unsignedSmallInteger('reports_count')->default(1)->comment('Nº LAUDOS');

            // Datas do fluxo
            $table->date('contact_date')->nullable()->comment('DATA DO CONTATO');
            $table->date('inspection_date')->nullable()->comment('DATA DA VISTORIA');
            $table->time('inspection_time')->nullable()->comment('HORA');
            $table->date('report_sent_at')->nullable()->comment('DATA DE ENVIO LAUDO');

            // Status
            $table->string('status', 30)->default('agendar_vistoria')->index()->comment('STATUS');

            // Atribuição (analista Spire que vai realizar/agendar)
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete()
                ->comment('Analista Spire responsável pela vistoria');

            // Observações
            $table->text('remark')->nullable()->comment('REMARK');

            // Auditoria
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'inspection_date']);
            $table->index(['assigned_to_user_id', 'inspection_date', 'inspection_time']);
            $table->unique(['tenant_id', 'claim_number'], 'inspections_tenant_claim_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
