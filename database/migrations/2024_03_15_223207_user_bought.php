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
        Schema::create('user_bought', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('listing_id')->constrained('listings');
            $table->primary(['user_id', 'listing_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bought');
    }
};
