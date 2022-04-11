<div>
    <x-action-section>
        <x-slot name="content">
            <div class="space-y-6">
                @if($this->syndications->isNotEmpty())
                    @foreach($this->syndications as $syndication)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('syndications.edit', ['syndication' => $syndication->id]) }}">
                                    {{ $syndication->slug }}
                                </a>
                            </div>

                            <div class="flex items-center">
                                @if($this->hasSyndications($syndication))
                                    <button wire:click="downloadLinksFile('{{ $syndication->id }}')" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                        {{ __('Download urls') }}
                                    </button>

                                    <a href="{{ $syndication->full_url }}" data-copy="{{ $syndication->copiedValue() }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                        {{ __('Copy urls') }}
                                    </a>

                                    <a href="{{ $syndication->full_url }}" data-copy="{{ '{'.$syndication->copiedValueSpintax().'}' }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                        {{ __('Copy Spintax') }}
                                    </a>
                                    
                                @endif

                                <a href="{{ route('syndications.edit', ['syndication' => $syndication->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Edit') }}
                                </a>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmSyndicationDeletion({{ $syndication->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->syndications->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->syndications->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No syndication yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingSyndicationDeletion">
        <x-slot name="title">
            {{ __('Delete Syndication') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this syndication?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingSyndicationDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteSyndication" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
