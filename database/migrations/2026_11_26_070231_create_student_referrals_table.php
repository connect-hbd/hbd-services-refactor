
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_referrals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('known_by', 100);

            $table->text('known_by_details')->nullable();

            $table->string('employee')->nullable();

            $table->string('expo')->nullable();

            $table->boolean('permission')->default(false);

            $table->timestamps();

            $table->index(['student_id', 'known_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_referrals');
    }
};
