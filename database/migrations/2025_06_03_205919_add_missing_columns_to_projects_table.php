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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('projects', 'task')) {
                $table->string('task')->nullable();
            }
            if (!Schema::hasColumn('projects', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('projects', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('projects', 'status')) {
                $table->enum('status', ['Not Started', 'In Progress', 'Completed', 'On Hold'])->default('Not Started');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $columns = ['description', 'task', 'start_date', 'end_date', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
