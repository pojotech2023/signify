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
        Schema::create('order_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('task_name');
            $table->string('task_priority');
            $table->dateTime('entry_time');
            $table->dateTime('completion_expected_by');
            $table->text('description');
            $table->string('whatsapp_audio')->nullable();
            $table->text('attachments');
            $table->string('vendor_name');
            $table->string('vendor_mobile');
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->foreignId('created_by')->constrained('internal_users')->onDelete('cascade');
            $table->string('status')->default('New');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tasks');
    }
};
