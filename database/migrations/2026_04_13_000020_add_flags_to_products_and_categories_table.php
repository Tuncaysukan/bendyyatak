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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_bestseller')->default(false)->after('is_featured');
            $table->boolean('is_new_arrival')->default(false)->after('is_bestseller');
            $table->boolean('show_on_slider')->default(false)->after('is_new_arrival');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('image');
            $table->boolean('is_bestseller')->default(false)->after('is_featured');
            $table->boolean('show_on_slider')->default(false)->after('is_bestseller');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_bestseller', 'is_new_arrival', 'show_on_slider']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_bestseller', 'show_on_slider']);
        });
    }
};
