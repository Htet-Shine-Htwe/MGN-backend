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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('current_subscription_id')->nullable()->constrained('subscriptions','id')->nullOnDelete();
            $table->timestamp('subscription_end_date')->nullable();
            $table->string('user_code',125)->unique();
            $table->string('password');
            $table->boolean('active')->default(1);
            $table->timestamp('last_login_at')->nullable();
            $table->string("background_color", 7)->default("#ffffff")->nullable();
            $table->foreignId('avatar_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['user_code','name','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
