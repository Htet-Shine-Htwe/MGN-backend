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
        Schema::create('base_sections', function (Blueprint $table) {
            $table->id();
            $table->string("section_name",115);
            $table->text("section_description");
            $table->smallInteger("component_limit")->default(10);

            $table->index('section_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_sections');
    }
};
