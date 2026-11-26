
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immigration_refusals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('country');
            $table->date('refusal_date')->nullable();

            $table->text('grounds')->nullable();

            // Refusal Letter
            $table->string('document_path')->nullable();
            $table->string('document_name')->nullable();

            // Multiple refusal ordering
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['student_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immigration_refusals');
    }
};
