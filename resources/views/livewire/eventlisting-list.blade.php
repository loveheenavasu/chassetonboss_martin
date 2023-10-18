<div>
    <x-jet-action-section>
        <x-slot name="title">
            {{ __('Manage Email Lists') }}
        </x-slot>

        <x-slot name="description">
            Contains sets of emails.
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if ($this->eventlistings->isNotEmpty())
                    @foreach ($this->eventlistings as $listing)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('eventlistings.show', ['eventlisting' => $listing->id]) }}" target="_blank">{{ $listing->name }}</a>
                                <small class="text-gray-400 ml-2">Emails total: {{ $listing->countallemails() }}, in pool: {{ $listing->countallemailsinpoll() }}</small>

                            </div>

                            <div class="flex items-center">

                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none" wire:click="exportNotInPollList({{ $listing->id}})">
                                    {{ __('Export Pool') }}
                                </button>

                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none" wire:click="exportInPollList({{ $listing->id}})">
                                    {{ __('Export Used') }}
                                </button>

                                <a href="{{ route('eventlistings.show', ['eventlisting' => $listing->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Details') }}
                                </a>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmingReset({{ $listing->id}})">
                                    {{ __('Reset') }}
                                </button>
                                <button class="cursor-pointer ml-6 text-sm text-green-500 focus:outline-none" wire:click="confirmingClone({{ $listing->id}})">
                                    {{ __('Clone') }}
                                </button>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmingListingDeletion({{ $listing->id}})">
                                    {{ __('Delete') }}
                                </button>

                            </div>
                        </div>
                    @endforeach
                    @if($this->eventlistings->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->eventlistings->fragment('eventlistings')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No Email lists yet.') }}</div>
                @endif
            </div>
        </x-slot>
    </x-jet-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingListingDeletion">
        <x-slot name="title">
            {{ __('Delete List') }}
        </x-slot>

        <x-slot name="content">
            @if($this->listingBeingDeleted)
                {{ __('Are you sure you want to delete connection :name?', ['name' => $this->listingBeingDeleted->name]) }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingListingDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteEventList" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
    <!-----reset modal--->
    <x-jet-confirmation-modal wire:model="confirmingReset">
        <x-slot name="title">
            {{ __('Reset List') }}
        </x-slot>

        <x-slot name="content">
            @if($this->listingBeingDeleted)
                {{ __('Are you sure you want to reset this :name?', ['name' => $this->listingBeingDeleted->name]) }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingReset')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="resetConnection" wire:loading.attr="disabled">
                {{ __('Reset') }}
            </x-jet-danger-button>

        </x-slot>
    </x-jet-confirmation-modal>
    <!-----clone modal--->
    <x-jet-confirmation-modal wire:model="confirmingClone">
        <x-slot name="title">
            {{ __('Clone List') }}
        </x-slot>

        <x-slot name="content">
            @if($this->listingBeingDeleted)
                {{ __('Are you sure you want to clone this :name?', ['name' => $this->listingBeingDeleted->name]) }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingClone')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="cloneConnection" wire:loading.attr="disabled">
                {{ __('Clone') }}
            </x-jet-danger-button>

        </x-slot>
    </x-jet-confirmation-modal>

</div>
