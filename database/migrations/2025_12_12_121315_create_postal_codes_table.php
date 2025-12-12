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
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->index()->comment('CEP sem formatação');
            $table->string('code_range', 8)->nullable()->comment('Faixa do CEP');
            $table->string('state', 2)->index()->comment('UF');
            $table->string('city', 250)->index()->comment('Cidade');
            $table->string('street', 250)->nullable()->comment('Logradouro');
            $table->string('complement', 250)->nullable()->comment('Complemento');
            $table->string('neighborhood', 250)->nullable()->comment('Bairro');
            $table->timestamps();

            $table->index(['state', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
