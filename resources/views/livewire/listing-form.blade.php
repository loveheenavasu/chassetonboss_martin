<x-jet-form-section submit="save">
    <x-slot name="title">
        List data
    </x-slot>

    <x-slot name="description">
        General information.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="listing.name" autofocus />
            <x-jet-input-error for="listing.name" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button>
            {{ __(!$this->listing->exists ? 'Create' : 'Update') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
