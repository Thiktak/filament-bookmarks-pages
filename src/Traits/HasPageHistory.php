<?php

namespace Thiktak\FilamentBookmarks\Traits;

use Illuminate\Support\Facades\URL;

trait HasPageHistory
{
    public static array $pageHistoryQueryStringAsPage = [];

    public static function getPageHistoryQueryStringAsPage(): array
    {
        return static::$pageHistoryQueryStringAsPage;
    }

    public function mountHasPageHistory()
    {
        /*    dd(
            $this->getTitle()
        );
        //dd($this->getData(), app('view')); //->getData());
    }

    static public function bootHasPageHistory()
    {*/
        if (! auth()->id()) {
            return;
        }

        $base = URL::to('');
        $queries = self::getPageHistoryQueryStringAsPage();

        // Extract queries for unique URL
        $args = count($queries) > 0 ? request()->all($queries) : [];
        $queryString = count($args) > 0 ? '?' . http_build_query($args) : null;

        $url = str_ireplace($base, '', request()->url() . $queryString);

        if (substr($url, 0, 10) == '/livewire/') {
            return;
        }

        \Thiktak\FilamentBookmarks\Models\History::query()
            ->updateOrCreate([
                'user_id' => auth()->id(),
                'url' => $url,
            ], [
                'title' => $this->getTitle(),
                'icon' => $this->getNavigationIcon(),
            ])
            ->touch();

        // Clean
        \Thiktak\FilamentBookmarks\Models\History::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('updated_at')
            ->get()
            ->skip(10 + 2) // @TODO: config
            ->each(fn ($row) => $row->delete());
    }
}
