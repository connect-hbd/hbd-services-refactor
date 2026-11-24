
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

            // Globally unique phone number
            $table->string('phone', 20)->unique();

            $table->boolean('is_whatsapp')->default(false);

            $table->unsignedTinyInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['student_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_phones');
    }
};
