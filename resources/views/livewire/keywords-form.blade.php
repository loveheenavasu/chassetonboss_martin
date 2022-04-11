<div>
    <x-form-section submit="submit">
        <x-slot name="title">
            Add Keyword
        </x-slot>

        <x-slot name="description">
            Manage Keyword.
        </x-slot>

        <x-slot name="form">
            <!-- <div class="col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="keywords.name" autofocus />
                <x-jet-input-error for="keywords.name" class="mt-2" />
            </div> -->
             <div class="col-span-6">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="10" wire:model.defer="keywords.name"></textarea>
                    <x-jet-input-error for="keywords.name" class="mt-2" />
                </div>
            <div class="col-span-6">
               <!--  <x-jet-label for="type" value="{{ __('Type') }}" />
                    <x-select name="type" class="mt-1" wire:model="keywords.type">
                        <option value=""></option>
                        
                            <option value="webmail">
                               webmail
                            </option>
                            <option value="Corporatemail">
                               Corporatemail
                            </option>
                    </x-select>
                <x-jet-input-error for="keywords.type" class="mt-2" /> -->
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->keywords->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
