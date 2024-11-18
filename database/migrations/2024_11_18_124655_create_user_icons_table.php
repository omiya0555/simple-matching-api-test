<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIconsTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_icons', function (Blueprint $table) {
            $table->id();
            $table->string('icon_path'); // アイコン画像パス
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_icons');
    }
}
