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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('aggregator_forms')->onDelete('cascade');
            $table->string('task_priority');
            $table->date('entry_time');
            $table->string('delivery_needed_by');
            $table->text('description');
            $table->string('whatsapp_audio')->nullable();
            $table->text('attachments');
            $table->string('vendor_name');
            $table->string('vendor_mobile');
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('whatsapp_message')->nullable();
            $table->foreignId('created_by')->constrained('internal_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
