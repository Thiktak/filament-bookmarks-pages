<?php

namespace Thiktak\FilamentBookmarks;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentBookmarksPlugin implements Plugin
{

    public function getId(): string
    {
        return 'FilamentBookmarks';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }




    public string $menuPlacement   = 'UserMenu';
    public bool $lastPagesActivate = 'UserMenu';
    public int $lastPagesHistory   = 'UserMenu';

    public function menuPlacement(string $menuPlacement): static
    {
        $this->menuPlacement = $menuPlacement;
        return $this;
    }

    public function activateLastPages(bool $activate = true, int $history = 10): static
    {
        $this->lastPagesActivate = $activate;
        $this->lastPagesHistory = $history;
        return $this;
    }
}
