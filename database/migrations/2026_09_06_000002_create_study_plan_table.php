
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->unique()
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('target_degree')->nullable();

            $table->timestamps();
        });

        Schema::create('study_plan_choices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('study_plan_id')
                ->constrained('study_plans')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('sort_order')->default(0);

            $table->string('program');
            $table->string('country');
            $table->string('start_date');

            $table->text('summary')->nullable();

            $table->timestamps();

            $table->index(['study_plan_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_plan_choices');
        Schema::dropIfExists('study_plans');
    }
};
