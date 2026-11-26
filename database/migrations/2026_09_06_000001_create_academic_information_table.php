<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_information', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->unique()
                ->constrained('students')
                ->cascadeOnDelete();

            // Highest Qualification
            $table->string('highest_degree')->nullable();
            $table->string('highest_institution')->nullable();
            $table->string('highest_grad_date')->nullable(); // YYYY-MM or YYYY-MM-DD
            $table->string('highest_gpa')->nullable();
            $table->string('highest_max_gpa')->nullable();

            // Education History (JSON array of entries)
            $table->json('education_history')->nullable();          

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_information');
    }
};
