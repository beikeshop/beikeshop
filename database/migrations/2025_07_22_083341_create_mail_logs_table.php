<?php

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
        if (!Schema::hasTable('mail_logs')) {
            Schema::create('mail_logs', function (Blueprint $table) {
                $table->comment('邮件发送日志表');
                $table->id()->comment('ID');
                $table->string('to_email')->comment('收件人邮箱');
                $table->string('to_name')->nullable()->comment('收件人姓名');
                $table->string('from_email')->nullable()->comment('发件人邮箱');
                $table->string('from_name')->nullable()->comment('发件人姓名');
                $table->string('subject')->comment('邮件主题');
                $table->longText('content')->nullable()->comment('邮件内容');
                $table->string('mail_type')->nullable()->comment('邮件类型');
                $table->enum('status', ['pending', 'sent', 'failed'])->default('pending')->comment('发送状态');
                $table->text('error_message')->nullable()->comment('错误信息');
                $table->json('headers')->nullable()->comment('邮件头信息');
                $table->json('attachments')->nullable()->comment('附件信息');
                $table->timestamp('sent_at')->nullable()->comment('发送时间');
                $table->timestamp('created_at')->nullable()->comment('创建时间');
                $table->timestamp('updated_at')->nullable()->comment('更新时间');

                $table->index(['to_email', 'created_at']);
                $table->index(['status', 'created_at']);
                $table->index('mail_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('mail_logs')) {
            Schema::dropIfExists('mail_logs');
        }
    }
};
