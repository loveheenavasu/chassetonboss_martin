<div>
    <x-form-section submit="submit">
        <x-slot name="form">

            <div class="col-span-2">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="groups.name" />
                <x-jet-input-error for="groups.name" class="mt-2" />
            </div>

            <div class="col-span-2">
                <x-jet-label for="affiliate_link" value="{{ __('No. Of Accounts') }}" />
                <x-jet-input id="affiliate_link" type="text" class="mt-1 block w-full" wire:model.defer="groups.accounts" />
                <x-jet-input-error for="groups.accounts" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->group->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
