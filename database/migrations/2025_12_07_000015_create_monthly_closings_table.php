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
        // Monthly Closings (Fechamentos mensais - base)
        Schema::create('monthly_closings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->string('status', 50)->default('open');
            $table->date('closing_date')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tenant_id', 'brand_id', 'year', 'month']);
            $table->index(['year', 'month']);
        });

        // Monthly Closing Items (OS vinculadas ao fechamento)
        Schema::create('monthly_closing_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('monthly_closing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->string('service_order_number', 20);
            $table->string('status', 50);
            $table->date('completed_at')->nullable();
            $table->decimal('parts_total', 12, 2)->default(0);
            $table->decimal('labor_total', 12, 2)->default(0);
            $table->decimal('travel_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();

            $table->index('monthly_closing_id');
            $table->index('service_order_id');
        });

        // Monthly Closing Discounts (Descontos aplicados)
        Schema::create('monthly_closing_discounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('monthly_closing_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index('monthly_closing_id');
        });

        // Monthly Closing Summaries (Resumo por tipo de serviço)
        Schema::create('monthly_closing_summaries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('monthly_closing_id')->constrained()->cascadeOnDelete();
            $table->string('category', 100);
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();

            $table->index('monthly_closing_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_closing_summaries');
        Schema::dropIfExists('monthly_closing_discounts');
        Schema::dropIfExists('monthly_closing_items');
        Schema::dropIfExists('monthly_closings');
    }
};
