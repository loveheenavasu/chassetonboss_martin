<div>
<x-jet-form-section submit="submit">
    <x-slot name="title">
        Proxy data
    </x-slot>

    <x-slot name="description">
        General information.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="proxy.name" autofocus />
            <x-jet-input-error for="proxy.name" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->proxy->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
</x-jet-form-section>
</div>