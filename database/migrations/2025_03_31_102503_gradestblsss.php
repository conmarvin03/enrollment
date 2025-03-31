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
           Schema::create('GradeSubmissions', function (Blueprint $table) {
            $table->id();
            $table->string('gradeName', 50);
            $table->string('section', 100);
            $table->string('subject', 10);
            $table->string('semester', 10);
            $table->string('year', 10);
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
