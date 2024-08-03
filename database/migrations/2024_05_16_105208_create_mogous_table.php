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
        Schema::create('mogous', function (Blueprint $table) {
            $table->id();
            $table->string('rotation_key')->default('alpha');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('author')->nullable();
            $table->string('cover');
            $table->smallInteger('status')->default(0);
            $table->smallInteger('finish_status')->default(0);
            $table->smallInteger('legal_age')->default(0);
            $table->smallInteger('rating')->default(0);
            $table->smallInteger('mogou_type')->default(0);
            $table->year('released_year')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mogous');
    }
};
