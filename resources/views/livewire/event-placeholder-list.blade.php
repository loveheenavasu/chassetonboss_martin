<div>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Content') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if($this->eventplaceholders->isNotEmpty())
                    @foreach($this->eventplaceholders as $eventplaceholder)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('eventplaceholders.edit', ['eventplaceholder' => $eventplaceholder->id]) }}">
                                    {{ $eventplaceholder->name }}
                                </a>
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('eventplaceholders.edit', ['eventplaceholder' => $eventplaceholder->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Edit') }}
                                </a>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmPlaceholderDeletion({{ $eventplaceholder->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div>{{ __('No Placeholders yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingPlaceholderDeletion">
        <x-slot name="title">
            {{ __('Delete Placeholder') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this Placeholder?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingPlaceholderDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deletePlaceholder" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
