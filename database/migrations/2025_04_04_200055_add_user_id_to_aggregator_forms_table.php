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
        Schema::table('aggregator_forms', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->string('how_heard')->nullable()->after('mobile_no');
            $table->string('remarks')->nullable()->after('how_heard');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aggregator_forms', function (Blueprint $table) {
            //
        });
    }
};
