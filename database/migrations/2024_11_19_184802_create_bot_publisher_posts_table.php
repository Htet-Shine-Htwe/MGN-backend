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
        Schema::create('bot_publisher_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_publisher_id')->constrained()->onDelete('cascade');
            $table->foreignId('mogou_id')->nullable()->constrained();
            $table->unsignedBigInteger('sub_mogou_id')->nullable();
            $table->unsignedBigInteger('social_channel_id');
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_publisher_posts');
    }
};
