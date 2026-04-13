<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->date('birthday')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birthday');
            $table->boolean('is_active')->default(true)->after('gender');
            $table->boolean('email_verified')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'birthday', 'gender', 'is_active', 'email_verified']);
        });
    }
};
