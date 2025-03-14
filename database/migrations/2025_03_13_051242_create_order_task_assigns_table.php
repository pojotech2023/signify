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
        Schema::create('order_task_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('executive_id')->constrained('internal_users')->onDelete('cascade');
            $table->foreignId('order_task_id')->constrained('order_tasks')->onDelete('cascade');
            $table->string('status')->default('New');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_task_assigns');
    }
};
