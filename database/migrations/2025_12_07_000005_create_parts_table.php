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
        Schema::create('parts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('part_code', 60)->comment('SKU único');
            $table->string('description');
            $table->string('short_description')->nullable();
            $table->string('unit', 10)->default('UN')->comment('UN, PC, KIT');
            $table->string('ncm', 15)->nullable();
            $table->string('cest', 15)->nullable();
            $table->unsignedTinyInteger('origin')->nullable()->comment('0-Nacional, 1-Importado, etc.');
            $table->string('ean', 20)->nullable();
            $table->string('ean_packaging', 20)->nullable();
            $table->string('manufacturer_code', 100)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('net_weight', 10, 3)->nullable();
            $table->decimal('gross_weight', 10, 3)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('depth', 10, 2)->nullable();
            $table->unsignedInteger('min_stock')->nullable();
            $table->unsignedInteger('max_stock')->nullable();
            $table->string('location', 100)->nullable();
            $table->boolean('is_display')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('bling_id')->nullable()->comment('ID integração Bling');
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'part_code']);
            $table->index('part_code');
            $table->index('description');
            $table->index('bling_id');
        });

        Schema::create('bill_of_materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_model_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('line_position', 50)->nullable();
            $table->boolean('is_provided')->default(true)->comment('Fornecido pelo fabricante');
            $table->timestamps();

            $table->unique(['product_model_id', 'part_id']);
            $table->index('product_model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_materials');
        Schema::dropIfExists('parts');
    }
};
