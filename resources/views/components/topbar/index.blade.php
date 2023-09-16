<div>
    {{ $this->bookmarkAction }}

    <x-filament-actions::modals /> <!-- With or without -->
</div>
{{--
    
    <x-filament-actions::group :actions="[$this->bookmarkAction]" />
    {{-- {{ $this->getAction() }} --}}

{{--
        <x-filament::icon-button :badge="1" wire:click="openBookmarkAction" color="gray" icon="heroicon-o-bookmark"
        x--icon-alias="panels::topbar.open-database-notifications-button" icon-size="lg" :label="__('filament-panels::layout.actions.open_database_notifications.label')" />
    
</div> --}}
