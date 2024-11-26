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
        Schema::create('bot_social_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_publisher_id')->constrained()->onDelete('cascade');
            $table->foreignId('social_channel_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_social_channels');
    }
};
