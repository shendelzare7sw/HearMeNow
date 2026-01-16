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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->unsignedBigInteger('storage_used')->default(0)->comment('in bytes')->after('avatar');
            $table->unsignedBigInteger('storage_limit')->default(5368709120)->comment('5GB in bytes')->after('storage_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'storage_used', 'storage_limit']);
        });
    }
};
