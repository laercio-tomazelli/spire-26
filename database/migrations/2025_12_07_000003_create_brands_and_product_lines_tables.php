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
        Schema::create('product_lines', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100)->comment('Linha Branca, Linha Marrom, Informática');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('manufacturer_id');
        });

        Schema::create('brand_product_line', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_line_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['brand_id', 'product_line_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_product_line');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('product_lines');
    }
};
