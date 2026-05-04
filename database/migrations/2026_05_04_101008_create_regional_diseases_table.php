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
        Schema::create('regional_diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('level')->default('Low'); // Low, Medium, High
            $table->string('distance')->nullable(); // e.g., '50km radius', 'Next Town'
            $table->string('trend')->default('stable'); // spreading, stable, decreasing
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regional_diseases');
    }
};
