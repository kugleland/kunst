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
        Schema::create('art_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('medium')->nullable();
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('depth')->nullable();
            $table->string('year')->nullable();
            $table->float('price')->nullable();
            $table->foreignId('artist_id')->constrained('artists');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('art_pieces');
    }
};
