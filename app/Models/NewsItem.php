<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Новость
 *
 * @property integer $id ID
 * @property string $title Заголовок
 * @property string $text Текст
 * @property string $image Ссылка на изображение
 * @property integer $rating Рейтинг
 * @property string $source Ресурс
 * @property string $source_link Ссылка на исходную статью
 *
 */
class NewsItem extends Model
{
    use HasFactory;

    protected $guarded = [];


}
