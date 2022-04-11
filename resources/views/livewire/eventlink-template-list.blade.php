<div>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Link Templates') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if($this->contents->isNotEmpty())
                    @foreach($this->contents as $content)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('eventlinktemplate.show', ['eventlinktemplate' => $content->id]) }}">
                                    {{ $content->name }}
                                </a>
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('eventlinktemplate.show', ['eventlinktemplate' => $content->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Edit') }}
                                </a>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmContentDeletion({{ $content->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div>{{ __('No link template yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingContentDeletion">
        <x-slot name="title">
            {{ __('Delete Link Template') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this link template?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingContentDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteContent" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
