
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_english_proficiency_exams', function (Blueprint $table) {
            $table->id();

            $table->foreignId('english_proficiency_id')
                ->constrained('student_english_proficiencies')
                ->cascadeOnDelete();

            $table->string('exam_name');
            $table->unsignedSmallInteger('year');

            // Exam Scores
            $table->decimal('overall_score', 5, 2);
            $table->decimal('listening_score', 5, 2)->nullable();
            $table->decimal('reading_score', 5, 2)->nullable();
            $table->decimal('writing_score', 5, 2)->nullable();
            $table->decimal('speaking_score', 5, 2)->nullable();

            $table->timestamps();

            $table->index(['exam_name', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_english_proficiency_exams');
    }
};
