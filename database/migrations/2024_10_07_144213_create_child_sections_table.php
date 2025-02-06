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
        Schema::create('child_sections', function (Blueprint $table) {
            $table->id();
            $table->string("pivot_key", 115);
            $table->smallInteger('is_visible')->default(1);
            $table->foreignId("base_section_id")->constrained()->onDelete('cascade');
            $table->index('pivot_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_sections');
    }
};
