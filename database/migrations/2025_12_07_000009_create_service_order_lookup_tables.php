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
        // Lookup Tables for Service Orders
        Schema::create('service_order_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });

        Schema::create('service_order_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });

        Schema::create('service_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100)->comment('Reparo, Instalação, etc.');
            $table->string('color', 50)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });

        Schema::create('service_locations', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100)->comment('Balcão, Domicílio, Depósito');
            $table->string('color', 50)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });

        Schema::create('repair_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code');
        });

        Schema::create('tracking_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('color', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('accept_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 50);
            $table->string('text_color', 50)->nullable();
            $table->string('bg_color', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('closing_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('comment_privacies', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->string('color', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('evidence_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('file_name_pattern')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->enum('applies_to', ['os', 'exchange', 'both'])->default('both');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidence_types');
        Schema::dropIfExists('comment_privacies');
        Schema::dropIfExists('closing_types');
        Schema::dropIfExists('accept_statuses');
        Schema::dropIfExists('tracking_statuses');
        Schema::dropIfExists('repair_types');
        Schema::dropIfExists('service_locations');
        Schema::dropIfExists('service_types');
        Schema::dropIfExists('service_order_types');
        Schema::dropIfExists('service_order_statuses');
    }
};
