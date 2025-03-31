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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('gsID', 50);
            $table->string('grade', 100);
            $table->string('remark', 100);
            $table->string('kldID', 10);
            $table->string('semester', 10);
            $table->string('year', 10);
            $table->string('section', 50);
            $table->string('tID', 50);
            $table->string('status', 50);
            $table->timestamps();
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
