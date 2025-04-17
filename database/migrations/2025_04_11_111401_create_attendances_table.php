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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_user_id')->constrained('internal_users')->onDelete('cascade');
            $table->date('date');
            $table->time('check_in_time');
            $table->string('check_in_location');
            $table->time('check_out_time')->nullable();
            $table->string('check_out_location')->nullable();
            $table->enum('type', ['Check In', 'Check Out']);
            $table->string('lat');
            $table->string('long');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
