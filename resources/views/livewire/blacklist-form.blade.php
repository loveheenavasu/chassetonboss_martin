<div>
    <x-form-section submit="submit">
        <x-slot name="title">
            Add Blacklist
        </x-slot>

        <x-slot name="description">
            Manage Blacklist.
        </x-slot>

        <x-slot name="form">
             <div class="col-span-6">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="10" wire:model.defer="blacklist.name"></textarea>
                    <x-jet-input-error for="blacklist.name" class="mt-2" />
                </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->blacklist->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
