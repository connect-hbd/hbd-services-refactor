
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('profile_step')->default(1);

            // Student Account
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // Personal Information
            $table->string('student_number')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('marital_status', 30)->nullable();

            // Passport Information
            $table->string('passport_number')->nullable()->unique();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();

            // Service Information
            $table->string('service_type')->nullable();

            // Ownership
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('owner_team_id')
                ->nullable()
                ->constrained('teams')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
