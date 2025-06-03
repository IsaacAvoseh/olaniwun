<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('is_active');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $roleId = DB::table('roles')->where('name', $user->role)->value('id');
            if ($roleId) {
                DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('is_active');
        });


        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $roleName = DB::table('roles')->where('id', $user->role_id)->value('name');
            if ($roleName) {
                DB::table('users')->where('id', $user->id)->update(['role' => $roleName]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
