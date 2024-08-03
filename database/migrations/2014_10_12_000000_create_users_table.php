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
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('current_subscription_id')->nullable()->constrained('subscriptions','id')->nullOnDelete();
            $table->timestamp('subscription_end_date')->nullable();
            $table->string('user_code',125)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['user_code','name', 'email']);
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
