<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconIdToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('icon_id')->nullable()->after('profile_image');
            $table->foreign('icon_id')->references('id')->on('user_icons')->onDelete('set null');
            $table->dropColumn('profile_image'); // 既存のカラムを削除
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable();
            $table->dropForeign(['icon_id']);
            $table->dropColumn('icon_id');
        });
    }
}
