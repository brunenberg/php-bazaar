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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('company_id')->nullable()->constrained('company');
            $table->string('title');
            $table->string('description');
            $table->enum('type', ['verkoop', 'verhuur']);
            $table->boolean('bidding_allowed')->default(false);
            $table->string('image')->nullable();
            $table->boolean('active')->default(false);
            $table->integer('rental_days')->nullable();
            $table->integer('expires_in_days')->default('7');
            $table->integer('condition')->nullable();
            $table->string('wear_speed')->nullable();
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
