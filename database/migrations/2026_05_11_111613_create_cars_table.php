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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('brand');
            $table->string('model_year');
            $table->enum('type', ['sedan', 'suv', 'microbus', 'luxury']);
            $table->enum('transmission', ['auto', 'manual'])->default('auto');
            $table->enum('fuel_type', ['octane', 'cng', 'diesel', 'electric'])->default('octane');
            $table->decimal('price_per_day', 10, 2);
            $table->integer('capacity');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
