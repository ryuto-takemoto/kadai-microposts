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
        Schema::table('microposts', function (Blueprint $table) {
            $table->unsignedInteger('impressions')->default(0); // impressions カラムを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microposts', function (Blueprint $table) {
            $table->dropColumn('impressions'); // impressions カラムを削除
        });
    }
};