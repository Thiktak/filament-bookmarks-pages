<?php

namespace Thiktak\FilamentBookmarks;

use Filament\Actions;

trait HasPageBookmark
{
    protected function HasPageBookmarkGetHeaderActions(): array
    {
        return [
            ...($this->getHeaderActions()),
            Actions\Action::make()
                ->label('History'),
        ];
    }
}
