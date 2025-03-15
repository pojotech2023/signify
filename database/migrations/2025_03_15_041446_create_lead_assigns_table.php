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
        Schema::create('lead_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_user_id')->constrained('internal_users')->onDelete('cascade');
            $table->foreignId('lead_id')->constrained('aggregator_forms')->onDelete('cascade');
            $table->string('status')->default('New');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_assigns');
    }
};
