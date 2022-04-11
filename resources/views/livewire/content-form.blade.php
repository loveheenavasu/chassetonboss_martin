<x-form-section submit="submit">
    <x-slot name="form">
        <div class="col-span-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="content.name" />
            <x-jet-input-error for="content.name" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-jet-label for="file" value="{{ __('File') }}" />
            <input id="file" type="file" class="mt-1 block w-full" wire:model="file">
            <x-jet-input-error for="file" class="mt-2" />
            <p class="text-gray-500"><small>Allowed extensions: {{ implode(', ', $extensions) }}</small></p>
        </div>

        <div class="col-span-6">
            <x-jet-label for="content" value="{{ __('Content') }}" />
            <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="15" wire:model.defer="content.content"></textarea>
            <x-jet-input-error for="content.content" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __(!$this->content->exists ? 'Create' : 'Update') }}
        </x-jet-button>
    </x-slot>
</x-form-section>
