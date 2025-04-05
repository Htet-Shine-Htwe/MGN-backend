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
        Schema::create('application_configs', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->integer("monthly_subscriptions_target")->default(100);
            $table->integer("daily_subscriptions_target")->default(1000);
            $table->integer("daily_traffic_target")->default(1000);
            $table->string("logo")->nullable();
            $table->string("water_mark")->nullable();
            $table->string("intro_a")->nullable();
            $table->string("outro_a")->nullable();
            $table->string("intro_b")->nullable();
            $table->string("outro_b")->nullable();
            $table->integer("user_side_is_maintenance_mode")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_configs');
    }
};
