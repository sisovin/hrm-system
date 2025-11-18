<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add missing fields for registration
            $table->date('date_of_birth')->nullable()->after('email');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('gender');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time')->after('status');
            $table->date('hire_date')->nullable()->after('employment_type');
            $table->integer('experience_years')->default(0)->after('hire_date');
            
            // Rename hired_at to make it consistent
            $table->dropColumn('hired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'address',
                'employment_type',
                'hire_date',
                'experience_years'
            ]);
            $table->timestamp('hired_at')->nullable();
        });
    }
};
