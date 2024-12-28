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
        Schema::create('bot_publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token_key');
            $table->unsignedTinyInteger('type');
            $table->boolean('is_active')->default(true);
            $table->dateTime('last_activity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_publishers');
    }
};
