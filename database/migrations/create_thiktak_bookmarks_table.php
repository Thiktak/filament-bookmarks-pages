<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thiktak_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            //$table->morphs('bookmarkable')->nullable();
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('code')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('group')->nullable();
            $table->timestamps();
        });

        Schema::create('thiktak_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('code')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thiktak_bookmarks');
        Schema::dropIfExists('thiktak_history');
    }
};
