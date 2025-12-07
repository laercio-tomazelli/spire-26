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
        Schema::create('warehouses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['main', 'partner', 'buffer', 'defective'])->default('main');
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_brand_default')->default(false);
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete()->comment('Se depósito do posto');
            $table->unsignedBigInteger('bling_id')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
            $table->index('type');
        });

        Schema::create('inventory_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->string('part_code', 60);
            $table->integer('available_quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('pending_quantity')->default(0);
            $table->integer('defective_quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['warehouse_id', 'part_id']);
            $table->index('part_code');
        });

        // Lookup tables for transactions
        Schema::create('document_types', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 50);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique('type');
        });

        Schema::create('transaction_types', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 50);
            $table->string('description')->nullable();
            $table->enum('operation', ['in', 'out', 'transfer'])->default('in');
            $table->timestamps();

            $table->unique('type');
        });

        Schema::create('inventory_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->string('part_code', 60);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->cascadeOnDelete();
            $table->string('document_number', 50)->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('part_code');
            $table->index('document_number');
        });

        Schema::create('inventory_reserves', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->string('part_code', 60);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['reserved', 'fulfilled', 'cancelled'])->default('reserved');
            $table->text('observations')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();

            $table->index('part_code');
            $table->index('status');
        });

        Schema::create('inventory_pending', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->string('part_code', 60);
            $table->unsignedBigInteger('service_order_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->integer('quantity');
            $table->enum('status', ['pending', 'fulfilled', 'cancelled'])->default('pending');
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('part_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_pending');
        Schema::dropIfExists('inventory_reserves');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('warehouses');
    }
};
