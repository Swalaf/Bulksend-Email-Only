<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smtp_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('host');
            $table->integer('port')->default(587);
            $table->text('username'); // Encrypted
            $table->text('password'); // Encrypted
            $table->string('encryption')->default('tls'); // tls, ssl
            $table->string('from_address');
            $table->string('from_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('daily_limit')->default(500);
            $table->integer('monthly_limit')->default(10000);
            
            // Security & Validation
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_tested_at')->nullable();
            $table->text('last_test_error')->nullable();
            $table->enum('status', ['pending', 'verified', 'failed', 'suspended'])->default('pending');
            
            // Tracking
            $table->integer('emails_sent_today')->default(0);
            $table->integer('emails_sent_month')->default(0);
            $table->timestamp('last_used_at')->nullable();
            
            $table->timestamps();
            
            $table->unique(['user_id', 'name']);
            $table->index(['user_id', 'is_default']);
            $table->index(['user_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_accounts');
    }
};
