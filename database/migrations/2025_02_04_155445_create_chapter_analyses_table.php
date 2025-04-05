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

            if(config('database.default') == 'sqlite') {
                $table->unsignedBigInteger('sub_mogou_id');
                $table->unsignedBigInteger('mogou_id');
            }else{
                $table->foreignId('sub_mogou_id');
                $table->foreignId('mogou_id')->constrained()->onDelete('cascade');
            }
           
            $table->ipAddress('ip');
            $table->dateTime('date');
            $table->foreignId('user_id')->nullable();
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
