
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_emails', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Globally unique alternative email
            $table->string('email', 191)->unique();

            // Maximum 2 alternative emails: 0 and 1
            $table->unsignedTinyInteger('sort_order');

            $table->timestamps();

            // Prevent duplicate position for same student
            $table->unique(['student_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_emails');
    }
};
