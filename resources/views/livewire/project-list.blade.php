<div>
    <x-jet-action-section>
        <x-slot name="title">
            {{ __('Manage Lists') }}
        </x-slot>

        <x-slot name="description">
            Contains sets of emails.
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if ($this->projectlist->isNotEmpty())
                    @foreach ($this->projectlist as $projectlisting)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('projectlist.show', ['projectlist' => $projectlisting->id]) }}" target="_blank">{{ $projectlisting->name }}</a>
                            </div>

                            <div class="flex items-center">

                                <a href="{{ route('projectlist.show', ['projectlist' => $projectlisting->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Details') }}
                                </a>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmingListingDeletion({{ $projectlisting->id}})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->projectlist->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->projectlist->fragment('projectlist')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No lists yet.') }}</div>
                @endif
            </div>
        </x-slot>
    </x-jet-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingListingDeletion">
        <x-slot name="title">
            {{ __('Delete Listing') }}
        </x-slot>

        <x-slot name="content">
            @if($this->ruleRunning == true)
                {{ __("List Can't be deleteable. This list is used in current running rule. So you can not delete it.") }}
            @endif
            @if($this->listingBeingDeleted && $this->ruleRunning == false)
                {{ __('Are you sure you want to delete projectlist :name?', ['name' => $this->listingBeingDeleted->name]) }}
            @endif
        </x-slot>

        <x-slot name="footer">
            @if($this->ruleRunning == true)
                <x-jet-secondary-button wire:click="$toggle('confirmingListingDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>
            @endif
            @if($this->ruleRunning == false)
                <x-jet-secondary-button wire:click="$toggle('confirmingListingDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteList" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-jet-danger-button>
            @endif

        </x-slot>
    </x-jet-confirmation-modal>
</div>
