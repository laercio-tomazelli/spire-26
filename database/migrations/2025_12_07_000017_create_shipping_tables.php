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
        // Carriers (Transportadoras)
        Schema::create('carriers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('document', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tracking_url_template')->nullable();
            $table->string('api_endpoint')->nullable();
            $table->json('api_credentials')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('name');
        });

        // Shipments (Envios/Remessas)
        Schema::create('shipments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tracking_code', 50)->nullable();
            $table->enum('type', ['outbound', 'inbound'])->default('outbound');

            // Origin
            $table->string('origin_name')->nullable();
            $table->string('origin_document', 20)->nullable();
            $table->string('origin_address')->nullable();
            $table->string('origin_address_number', 20)->nullable();
            $table->string('origin_neighborhood', 100)->nullable();
            $table->string('origin_city', 100)->nullable();
            $table->char('origin_state', 2)->nullable();
            $table->string('origin_postal_code', 10)->nullable();

            // Destination
            $table->string('destination_name')->nullable();
            $table->string('destination_document', 20)->nullable();
            $table->string('destination_address')->nullable();
            $table->string('destination_address_number', 20)->nullable();
            $table->string('destination_neighborhood', 100)->nullable();
            $table->string('destination_city', 100)->nullable();
            $table->char('destination_state', 2)->nullable();
            $table->string('destination_postal_code', 10)->nullable();

            // Package details
            $table->decimal('weight', 10, 3)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('declared_value', 12, 2)->nullable();
            $table->unsignedInteger('volumes')->default(1);

            // Costs
            $table->decimal('shipping_cost', 12, 2)->nullable();
            $table->decimal('insurance_cost', 12, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();

            // Status
            $table->string('status', 50)->default('pending');
            $table->datetime('shipped_at')->nullable();
            $table->datetime('estimated_delivery_at')->nullable();
            $table->datetime('delivered_at')->nullable();

            // References
            $table->string('invoice_number', 20)->nullable();
            $table->string('invoice_key', 50)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('carrier_id');
            $table->index('tracking_code');
            $table->index('status');
        });

        // Shipment Items (Itens do envio)
        Schema::create('shipment_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->morphs('shippable'); // service_order, order, exchange
            $table->string('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->index('shipment_id');
        });

        // Shipment Tracking Events (Eventos de rastreamento)
        Schema::create('shipment_tracking_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->datetime('occurred_at');
            $table->string('status', 100);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index('shipment_id');
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_tracking_events');
        Schema::dropIfExists('shipment_items');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('carriers');
    }
};
