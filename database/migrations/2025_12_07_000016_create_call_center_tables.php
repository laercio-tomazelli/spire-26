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
        // Call Center Queues (Filas de atendimento)
        Schema::create('call_center_queues', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
        });

        // Call Center Queue Items (Itens na fila)
        Schema::create('call_center_queue_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('queue_id')->constrained('call_center_queues')->cascadeOnDelete();
            $table->foreignId('service_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone', 20)->nullable();
            $table->string('customer_name')->nullable();
            $table->string('subject')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('waiting');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('entered_at');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->timestamps();

            $table->index('queue_id');
            $table->index('status');
            $table->index('assigned_to');
        });

        // Call Center Interactions (Histórico de interações)
        Schema::create('call_center_interactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('queue_item_id')->nullable()->constrained('call_center_queue_items')->nullOnDelete();
            $table->foreignId('service_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['phone', 'email', 'whatsapp', 'chat', 'other'])->default('phone');
            $table->enum('direction', ['inbound', 'outbound'])->default('inbound');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->datetime('started_at');
            $table->datetime('ended_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('result', 100)->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('customer_id');
            $table->index('service_order_id');
            $table->index('user_id');
        });

        // Call Center Scripts (Scripts de atendimento)
        Schema::create('call_center_scripts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_center_scripts');
        Schema::dropIfExists('call_center_interactions');
        Schema::dropIfExists('call_center_queue_items');
        Schema::dropIfExists('call_center_queues');
    }
};
