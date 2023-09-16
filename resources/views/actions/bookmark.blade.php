<div>
    @forelse($links as $group => $groupData)
        <div>
            <h4 class="font-bold">{{ $group }}</h4>
            <div
                class="relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10 rounded border-1 mb-4">
                @forelse($groupData as $link)
                    <div class="flex justify-between items-center py-1 w-full bg-gray-200/10">
                        <div>
                            <x-filament::link :href="$link->getUrl()" class="w-full p-1">
                                <x-filament::icon :icon="$link->icon ?: 'heroicon-o-bookmark'" class="h-7 w-7 p-1 rounded-xl"
                                    style="color: {{ $link->color ?: 'inherit' }}" />
                                <span>{{ $link->title ?: $link->getUrl() }}</span>
                            </x-filament::link>
                        </div>
                        <div class="flex pe-1">
                            @if ($link->url == $url)
                                <x-filament::badge class="inline-block p-1 px-2 me-1" size="xs" color="success">
                                    This page
                                </x-filament::badge>
                            @endif
                            @if ($link->user_id)
                                <x-filament::badge class="inline-block p-1 px-2 me-1" size="xs">
                                    Public
                                </x-filament::badge>
                            @endif
                        </div>
                    </div>
                @empty
                    <div>
                        Empty - no links
                    </div>
                @endforelse
            </div>
        </div>
    @empty
        Empty - no links
    @endforelse
</div>
