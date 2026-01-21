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
        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_line_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100)->comment('TV, Monitor, Geladeira, etc.');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('product_line_id');
        });

        Schema::create('product_models', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('model_code', 100);
            $table->string('model_name')->nullable();
            $table->string('manufacturer_model')->nullable();
            $table->string('ean', 20)->nullable();
            $table->date('release_date')->nullable();
            $table->date('end_of_life_date')->nullable();
            $table->unsignedSmallInteger('warranty_months')->nullable()->default(12);
            $table->unsignedSmallInteger('promotional_warranty_months')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('brand_id');
            $table->index('product_category_id');
            $table->index('model_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_models');
        Schema::dropIfExists('product_categories');
    }
};
