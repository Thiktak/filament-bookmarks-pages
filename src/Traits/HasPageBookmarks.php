<?php

namespace Thiktak\FilamentBookmarks\Traits;

use Filament\Actions;

trait HasPageBookmarks
{
    protected function HasPageBookmarksGetHeaderActions(): array
    {
        dd(1);

        return [
            ...($this->getHeaderActions()),
            Actions\Action::make()
                ->label('History'),
        ];
    }
}
