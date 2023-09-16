<?php

namespace Thiktak\FilamentBookmarks\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Filament\Navigation\MenuItem;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Thiktak\FilamentBookmarks\FilamentBookmarksPlugin;

class Bookmarks
{
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();

        if (!$panel->hasPlugin('ThiktakFilamentBookmarks')) {
            return $next($request);
        }

        FilamentView::registerRenderHook(
            'panels::global-search.after',
            fn (): string => Blade::render('@livewire(\Thiktak\FilamentBookmarks\Livewire\TopbarBookmark::class)') //, [\'lazy\' => true])')
            //fn (): View => view('thiktak-filament-bookmarks::components.topbar.index'),
        );
        /*
        FilamentView::registerRenderHook(
            'panels::global-search.after',
            //fn (): string => Blade::render('@livewire(\'thiktak-filament-bookmarks::topbar-bookmark\')') //, [\'lazy\' => true])')
            //fn (): string => Blade::render('@livewire(\Thiktak\FilamentBookmarks\Livewire\TopbarBookmark::class)') //, [\'lazy\' => true])')
            fn (): View => view('thiktak-filament-bookmarks::components.topbar.index'),
            //scopes: \App\Filament\Resources\UserResource\Pages\EditUser::class,
        );*/

        //if (FilamentBookmarksPlugin::hasMenuPlacementUserMenu()) {
        $panel
            ->userMenuItems([
                MenuItem::make('Bookmarks')
                    ->label(__('themes::themes.themes'))
                    ->icon(config('themes.icon'))
                //->
                ,
            ]);
        //}

        return $next($request);
    }
}
