<div>
    <x-jet-action-section>
        <x-slot name="title">
            {{ __('Manage Proxy') }}
        </x-slot>

        <x-slot name="description">
           Information about saved proxy
        </x-slot>
        
        <x-slot name="content">
            <div class="space-y-6">
                @if ($this->proxys->isNotEmpty())
                    @foreach ($this->proxys as $proxy)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('proxy.show', ['proxy' => $proxy->id]) }}" target="_blank">{{ $proxy->name }}</a>
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('proxy.show', ['proxy' => $proxy->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Details') }}
                                </a>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmProxyDeletion({{ $proxy->id}})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->proxys->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->proxys->fragment('proxy')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No lists yet.') }}</div>
                @endif
            </div>
        </x-slot>
    </x-jet-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingProxyDeletion">
        <x-slot name="title">
            {{ __('Delete connections') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this account?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingProxyDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteProxy" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
