<?php

namespace Thiktak\FilamentBookmarks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Bookmark extends Model
{
    public $table = 'thiktak_bookmarks';

    public $fillable = [
        'user_id',
        //'bookmarkable_id', 'bookmarkable_type',
        'url',
        'color', 'group', 'title', 'icon',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if (!($page = @file_get_contents($model->url . '@'))) {
                $model->title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
                $model->code = 200;
            } else {
                $model->code = 404;
            }
            return $model;
        });
    }


    public function getUrl(): string
    {
        return $this->url;
        /*
        $routes = Route::getRoutes();

        $action = $routes->getByAction($this->bookmarkable_type);
        $action->setParameter('record', $this->bookmarkable_id);

        return route($action->getName(), $action->parameters());*/
    }
}
