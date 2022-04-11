<div>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Keywords') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if($this->blacklists->isNotEmpty())
                    @foreach($this->blacklists as $blacklist)
                        <div class="flex items-center justify-between">
                            <div>
                                {{ $blacklist->name }}
                            </div>

                            <div class="flex items-center">
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmBlacklistDeletion({{ $blacklist->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                     @if($this->blacklists->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->blacklists->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No blacklist list yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingBlacklistDeletion">
        <x-slot name="title">
            {{ __('Delete Blacklist') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this blacklist?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingBlacklistDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteBlacklist" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
