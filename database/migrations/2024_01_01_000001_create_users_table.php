<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'user', 'vendor'])->default('user');
            $table->boolean('is_active')->default(true);
            
            // Onboarding fields
            $table->enum('onboarding_step', ['welcome', 'business', 'smtp', 'campaign', 'complete'])->default('welcome')->nullable();
            $table->string('business_name')->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_website')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
