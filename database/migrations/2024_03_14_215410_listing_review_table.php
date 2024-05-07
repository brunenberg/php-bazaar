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
        Schema::create('listing_review', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('listing_id')->constrained('listings');
            $table->primary(['user_id', 'listing_id']);
            $table->text('review');
            $table->integer('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_review');
    }
};
