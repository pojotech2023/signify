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
        Schema::create('aggregator_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->foreignId('shade_id')->constrained()->onDelete('cascade');
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('unit')->nullable();
            $table->string('location')->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('design_service_need',['Yes','No']);
            $table->string('email_id')->unique(); // Unique constraint added
            $table->string('site_image')->nullable();
            $table->string('design_attachment')->nullable();
            $table->string('reference_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggregator_forms');
    }
};
