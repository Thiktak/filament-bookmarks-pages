<?php

namespace Thiktak\FilamentBookmarks;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

//use Thiktak\FilamentBookmarks\Commands\FilamentBookmarksCommand;
//use Thiktak\FilamentBookmarks\Testing\TestsFilamentBookmarks;

class FilamentBookmarksServiceProvider extends PackageServiceProvider
{
    public static string $name = 'thiktak-filament-bookmarks';

    public static string $viewNamespace = 'thiktak-filament-bookmarks';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            //->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('Thiktak/filament-bookmarks');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        Livewire::component('topbar-bookmark', \Thiktak\FilamentBookmarks\Livewire\TopbarBookmark::class);

        /*FilamentView::registerRenderHook(
            'panels::global-search.after',
            //fn (): string => Blade::render('@livewire(\'thiktak-filament-bookmarks::topbar-bookmark\')') //, [\'lazy\' => true])')
            fn (): string => Blade::render('@livewire(\Thiktak\FilamentBookmarks\Livewire\TopbarBookmark::class)') //, [\'lazy\' => true])')
            //fn (): View => view('thiktak-filament-bookmarks::components.topbar.index'),
            //scopes: \App\Filament\Resources\UserResource\Pages\EditUser::class,
        );*/

        // Handle Stubs
        /*if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/FilamentBookmarks/{$file->getFilename()}"),
                ], 'FilamentBookmarks-stubs');
            }
        }*/

        // Testing
        //Testable::mixin(new TestsFilamentBookmarks());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'thiktak-filament-bookmarks';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
        /*// AlpineComponent::make('FilamentBookmarks', __DIR__ . '/../resources/dist/components/FilamentBookmarks.js'),
            Css::make('FilamentBookmarks-styles', __DIR__ . '/../resources/dist/FilamentBookmarks.css'),
            Js::make('FilamentBookmarks-scripts', __DIR__ . '/../resources/dist/FilamentBookmarks.js'),*/];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentBookmarksCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_thiktak_bookmarks_table',
        ];
    }
}
