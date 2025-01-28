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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('pID');
            $table->string('kldID');
            $table->string('fName');
            $table->string('lName');
            $table->string('mName');
            $table->string('gender')->nullable();
            $table->string('bday')->nullable();
            $table->string('address')->nullable();
            $table->string('img');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
