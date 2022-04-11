<div>
    <x-form-section submit="submit">
        <x-slot name="title">
            Gmail Connection data
        </x-slot>

        <x-slot name="description">
            Account credentials.
           
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="project_listing_id" value="{{ __('Select a Project File') }}" />
                <x-select name="project_listing_id" class="mt-1" wire:model="gmailconnection.project_listing_id">
                    <option value=""></option>

                    @foreach($this->projectlistings as $projectlistings)

                    <option value="{{ $projectlistings->id }}">
                        {{ $projectlistings->name }}
                    </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="gmailconnection.project_listing_id" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-jet-label for="email_id" value="{{ __('Email Id') }}" />
                <x-jet-input id="email_id" type="text" class="mt-1 block w-full" wire:model.defer="gmailconnection.email_id" />
                <x-jet-input-error for="gmailconnection.email_id" class="mt-2" />
            </div>
            <div class="col-span-6">
                <x-jet-label for="groups_id" value="{{ __('Select a Group') }}" />
                <x-select name="groups_id" class="mt-1" wire:model="gmailconnection.group_id">
                    <option value=""></option>

                    @foreach($this->Groups as $groups)

                    <option value="{{ $groups->id }}">
                        {{ $groups->name }}
                    </option>
                    @endforeach
                </x-select>
            </div>
            
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->gmailconnection->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
