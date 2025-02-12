<?php

use App\Enum\MogousStatus;
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
        Schema::create('sub_mogous', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover')->nullable();

            $table->integer('status')->default(MogousStatus::DRAFT->value);
            $table->integer("chapter_number");
            $table->unsignedBigInteger('views')->default(0);

            $table->text('third_party_url')->nullable();
            $table->integer('third_party_redirect')->default(0);

            $table->integer('subscription_only')->default(0);
            $table->json('subscription_collection')->nullable();

            $table->string('prefix_upload')->nullable();

            $table->foreignId('mogou_id')->constrained()->onDelete('cascade');
            $table->morphs('creator');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_mogous');
    }
};
