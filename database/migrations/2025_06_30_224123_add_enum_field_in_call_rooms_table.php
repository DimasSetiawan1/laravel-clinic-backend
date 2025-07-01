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
        Schema::table('call_rooms', function (Blueprint $table) {
            $table->enum('status', ['Waiting', 'Ongoing','Finished' ,'Expired'])
                ->default('Waiting')
                ->after('doctor_id')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
