<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Заголовок');
            $table->text('text')->comment('Текст');
            $table->string('image')->nullable()->comment('Ссылка на изображение');
            $table->integer('rating')->comment('Рейтинг');
            $table->string('source')->comment('Ресурс');
            $table->string('source_link')->comment('Ссылка на исходную статью');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_items');
    }
};
