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
        Schema::create('chapter_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_mogou_id');
            $table->foreignId('mogou_id')->constrained()->onDelete('cascade');
            // $table->binary('ip');
            $table->ipAddress('ip');
            $table->dateTime('date');

            $table->index(['sub_mogou_id', 'mogou_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_analyses');
    }
};
