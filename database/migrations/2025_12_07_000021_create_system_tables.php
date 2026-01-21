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
        // Notifications (Notificações do sistema)
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Notification Preferences (Preferências de notificação por usuário)
        Schema::create('notification_preferences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('notification_type', 100);
            $table->boolean('email_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'notification_type']);
        });

        // System Settings (Configurações do sistema por tenant)
        Schema::create('system_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('group', 100);
            $table->string('key', 100);
            $table->text('value')->nullable();
            $table->string('type', 50)->default('string');
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();

            $table->unique(['tenant_id', 'group', 'key']);
            $table->index(['group', 'key']);
        });

        // Email Templates (Templates de e-mail)
        Schema::create('email_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('subject');
            $table->longText('body_html');
            $table->longText('body_text')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });

        // Sent Emails Log (Log de e-mails enviados)
        Schema::create('sent_emails', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('email_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('subject');
            $table->longText('body')->nullable();
            $table->string('status', 50)->default('sent');
            $table->text('error_message')->nullable();
            $table->string('message_id')->nullable();
            $table->datetime('opened_at')->nullable();
            $table->datetime('clicked_at')->nullable();
            $table->morphs('related');
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('to_email');
            $table->index('status');
        });

        // File Attachments (Anexos genéricos - polimórfico)
        Schema::create('file_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->morphs('attachable');
            $table->string('collection', 100)->default('default');
            $table->string('name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('disk', 50)->default('local');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->json('custom_properties')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index('collection');
        });

        // Audit Trail (Trilha de auditoria completa)
        Schema::create('audit_trails', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event', 50);
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('event');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
        Schema::dropIfExists('file_attachments');
        Schema::dropIfExists('sent_emails');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
};
