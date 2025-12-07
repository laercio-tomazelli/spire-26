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
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('partner_id')->nullable()->comment('Vínculo com Posto Autorizado');
            $table->unsignedBigInteger('manufacturer_id')->nullable()->comment('Vínculo com Fabricante');
            $table->unsignedBigInteger('customer_id')->nullable()->comment('Vínculo com Cliente final');

            // Tipo de usuário - determina permissões base e fluxo de navegação
            $table->enum('user_type', ['spire', 'partner', 'manufacturer', 'client'])->default('spire');

            // Para usuários de Posto (partner)
            $table->boolean('is_partner_admin')->default(false)->comment('Admin do posto, criado automaticamente');
            $table->unsignedBigInteger('created_by_user_id')->nullable()->comment('Usuário que criou (hierarquia partner)');

            $table->string('username', 50)->nullable()->unique()->comment('Para partners = código do posto');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('partner_id');
            $table->index('manufacturer_id');
            $table->index('customer_id');
            $table->index('user_type');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
