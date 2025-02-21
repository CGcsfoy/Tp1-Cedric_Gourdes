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
        Schema::create('films', function (Blueprint $table) {
            $table->id(); 
            $table->string('title', 255)->nullable(false); 
            $table->text('description'); 
            $table->year('release_year')->nullable(false); 
            $table->unsignedBigInteger('language_id')->nullable(false);
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->unsignedTinyInteger('original_language_id')->nullable();
            $table->unsignedTinyInteger('rental_duration')->nullable(false)->default(3);
            $table->decimal('rental_rate', 4, 2)->nullable(false)->default(4.99);
            $table->unsignedSmallInteger('length');
            $table->decimal('replacement_cost', 5, 2)->nullable(false)->default(19.99);
            $table->enum('rating', ['G', 'PG', 'PG-13', 'R', 'NC-17'])->default('G');
            $table->text('special_features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
