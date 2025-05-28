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
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('chat_room_id')->after('id')->nullable();
            $table->foreign('chat_room_id')
                ->references('id')
                ->on('chat_rooms')
                ->onDelete('set null');
            $table->index('chat_room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['chat_room_id']);
            $table->dropColumn('chat_room_id');
        });
    }
};
