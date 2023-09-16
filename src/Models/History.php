<?php

namespace Thiktak\FilamentBookmarks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class History extends Model
{
    public $table = 'thiktak_history';

    public $fillable = [
        'user_id',
        'url',
        'title',
        'icon',
        'code',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
        });
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function updateTitle(): static
    {
        /*if (empty($this->code)) {
            dd(file_get_contents('http://p101_lv10_matrix.test/matrix/reports/2/view'));

            $base = URL::to('');
            if (($page = @file_get_contents($base . $this->url))) {
                $this->title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
                $this->code = 200;
                //dd($model, $page);
            } else {
                $this->code = 404;
            }
            dd($this, $page, $base . $this->url);

            // Prevent update of timestamps
            $this->timestamps = false;
            $this->save();
        }*/

        return $this;
    }
}
