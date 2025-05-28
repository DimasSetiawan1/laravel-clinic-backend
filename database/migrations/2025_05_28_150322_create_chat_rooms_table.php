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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('orders_id')
                ->constrained('orders')
                ->onDelete('cascade');
            $table->foreignId('users_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('doctors_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('closed_at')->nullable();
            $table->enum('closed_by', ['system', 'doctor', 'user'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms',  function (Blueprint $table) {
            $table->dropForeign(['orders_id']);
            $table->dropForeign(['users_id']);
            $table->dropForeign(['doctors_id']);
        });
        Schema::dropIfExists('chat_rooms');
    }
};
