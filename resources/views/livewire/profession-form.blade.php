<div>
    <x-form-section submit="submit">
        <x-slot name="title">
            Add Profession Keyword
        </x-slot>

        <x-slot name="description">
            Manage Keyword.
        </x-slot>

        <x-slot name="form" >
            <div class="col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="profession.name" />
                <x-jet-input-error for="profession.name" class="mt-2" />
                </div>
            <div class="col-span-6">
                <x-jet-label for="keyword" value="{{ __('Keyword') }}" />
                <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="5" wire:model.defer="profession.keyword"></textarea>
                <x-jet-input-error for="profession.keyword" class="mt-2" />

            </div>
            <div class="col-span-6">
                <x-jet-label for="note" value="{{ __('Notes: Add multiple keyword with comma seprated') }}" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->profession->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
