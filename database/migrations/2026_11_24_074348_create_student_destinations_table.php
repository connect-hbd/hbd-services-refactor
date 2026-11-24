
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_destinations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

             $table->foreignUuid('country_id')
            ->nullable()
            ->constrained('countries')
            ->nullOnDelete();

            $table->timestamps();

            // Prevent duplicate destination for same student
            $table->unique(['student_id', 'country_id']);

            // Fast destination-wise student search
            $table->index(['country_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_destinations');
    }
};
