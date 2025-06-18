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
            $table->unsignedBigInteger('reply_to')->nullable(); // リプライ先の投稿ID
            $table->unsignedBigInteger('repost_from')->nullable(); // リポスト元の投稿ID

            $table->foreign('reply_to')->references('id')->on('microposts')->onDelete('cascade');
            $table->foreign('repost_from')->references('id')->on('microposts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microposts', function (Blueprint $table) {
            $table->dropForeign(['reply_to']);
            $table->dropForeign(['repost_from']);
            $table->dropColumn('reply_to');
            $table->dropColumn('repost_from');
        });
    }
};
