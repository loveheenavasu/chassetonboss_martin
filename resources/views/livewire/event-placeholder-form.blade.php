<div>
    <x-form-section submit="submit" enctype="multipart/form-data" >
       
        <x-slot name="form">
             <div class="col-span-12">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" name="name" type="text" class="mt-1 block w-full" wire:model.defer="eventplaceholder.name" />
                <x-jet-input-error for="eventplaceholder.name" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->eventplaceholder->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>