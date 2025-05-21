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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //patient_id
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            // doctor_id
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            // service
            $table->string('service');
            // price
            $table->integer('price');
            // payment_url
            $table->string('payment_url')->nullable();
            // status
            $table->enum('status', ['PENDING', 'PAID', 'EXPIRED'])->default('PENDING');
            //duration
            $table->integer('duration');
            //clinic_id
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            // schedule
            $table->dateTime('schedule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
