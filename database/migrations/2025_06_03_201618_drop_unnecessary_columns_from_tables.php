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
            $table->dropSoftDeletes();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'task',
                'start_date',
                'end_date',
                'status'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back columns to employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add back columns to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('task')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['Not Started', 'In Progress', 'Completed', 'On Hold'])->default('Not Started');
        });
    }
};
