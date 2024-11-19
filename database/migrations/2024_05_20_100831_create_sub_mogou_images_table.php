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
        Schema::create('sub_mogou_images', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->foreignId('mogou_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_mogou_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('page_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_mogou_images');
    }
};
