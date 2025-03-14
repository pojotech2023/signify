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
        Schema::create('order_executive_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_executive_id')->constrained('order_task_assigns')->onDelete('cascade');
            $table->text('remarks');
            $table->string('whatsapp_audio')->nullable();
            $table->string('geo_latitude');
            $table->string('geo_longitude');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_executive_tasks');
    }
};
