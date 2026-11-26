
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_english_proficiencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Exam Status
            $table->string('status', 20); // will, yes

            // Planned Exam (status = will)
            $table->string('exam_name')->nullable();
            $table->date('exam_date')->nullable();

            // Course Information
            $table->string('course_name')->nullable();
            $table->string('mode', 50)->nullable();
            // online, in_person_campus, one_on_one_intensive

            $table->decimal('target_score', 5, 2)->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_english_proficiencies');
    }
};
