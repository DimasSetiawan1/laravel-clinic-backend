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
         Schema::create('call_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('call_room_uid')->unique();
            $table->string('call_token')->nullable();
            $table->dateTime('expired_token')->nullable();
            $table->string('call_channel')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('patient_id')->references('id')->on("users")->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on("users")->onDelete('cascade');
            $table->enum('status', ['Waiting', 'Close', 'Ongoing'])->default('Waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
