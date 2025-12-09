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
        // Teams (Times/Equipes)
        Schema::create('teams', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->index('slug');
        });

        // Team Users (Usuários por time)
        Schema::create('team_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_leader')->default(false); // Líder do time
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });

        // Team Roles (Perfis atribuídos ao time - todos os membros herdam)
        Schema::create('team_roles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['team_id', 'role_id']);
        });

        // Team Permissions (Permissões diretas do time)
        Schema::create('team_permissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->boolean('granted')->default(true);
            $table->timestamps();

            $table->unique(['team_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_permissions');
        Schema::dropIfExists('team_roles');
        Schema::dropIfExists('team_users');
        Schema::dropIfExists('teams');
    }
};
