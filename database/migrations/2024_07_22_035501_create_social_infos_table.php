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
        Schema::create('social_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string("icon")->nullable();
            $table->string("cover_photo")->nullable();
            $table->string("url");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_infos');
    }
};
