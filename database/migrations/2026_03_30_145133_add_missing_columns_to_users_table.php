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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user', 'vendor'])->default('user')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->enum('onboarding_step', ['welcome', 'business', 'smtp', 'campaign', 'complete'])->default('welcome')->nullable()->after('is_active');
            $table->string('business_name')->nullable()->after('onboarding_step');
            $table->text('business_description')->nullable()->after('business_name');
            $table->string('business_website')->nullable()->after('business_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
