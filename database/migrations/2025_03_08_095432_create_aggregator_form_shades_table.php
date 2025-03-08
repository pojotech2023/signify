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
        Schema::create('aggregator_form_shades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aggregator_form_id')->constrained('aggregator_forms')->onDelete('cascade');
            $table->foreignId('shade_id')->constrained('shades')->onDelete('cascade');
            $table->string('selected_img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggregator_form_shades');
    }
};
