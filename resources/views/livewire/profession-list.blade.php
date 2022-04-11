<div>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Profession Keywords') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if($this->professions->isNotEmpty())
                    @foreach($this->professions as $profession)
                        <div class="flex items-center justify-between">
                            <div>
                                {{ $profession->name }}
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('profession.show', ['profession' => $profession->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Details') }}
                                </a>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmKeywordDeletion({{ $profession->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                     @if($this->professions->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->professions->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No profession keywords list yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingKeywordDeletion">
        <x-slot name="title">
            {{ __('Delete profession Keyword') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this profession keyword?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingKeywordDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteTemplate" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
