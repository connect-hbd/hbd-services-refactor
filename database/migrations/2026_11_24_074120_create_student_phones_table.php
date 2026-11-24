
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_phones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Globally unique alternative phone
            $table->string('phone', 20)->unique();

            // WhatsApp status
            $table->boolean('is_whatsapp')->default(false);

            // Maximum 2 alternative phones: 0 and 1
            $table->unsignedTinyInteger('sort_order');

            $table->timestamps();

            // Prevent duplicate position for same student
            $table->unique(['student_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_phones');
    }
};
