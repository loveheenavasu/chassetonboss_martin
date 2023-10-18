<div>
    <x-form-section submit="submit">
        <x-slot name="form">    
            @if($this->CurrentTooll == 'landingpage')
            <div class="col-span-3">
                <x-jet-label for="profile_id" value="{{ __('Profile *') }}" />
                <x-select name="profile_id" class="mt-1" wire:model.defer="landingpage.profile_id">
                  
                    <option value=""></option>
                       @foreach($this->profiles as $profile)
                        <option value="{{$profile->id}}" {{$this->landingpage->profile_id ? 'selected':''}}>
                            {{$profile->profile_name}}
                        </option>
                       @endforeach

                
                </x-select>
                <!-- <x-jet-input-error for="page.profile_id" class="mt-2" /> -->
            </div>
            @endif
            <div class="col-span-3">
                <x-jet-label for="slug" value="{{ __('Slug *') }}" />
                <x-jet-input id="slug" type="text" class="mt-1 block w-full" wire:model.defer="landingpage.slug" />
                <x-jet-input-error for="landingpage.slug" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="landing_template_id" value="{{ __('Template *') }}" />
                <x-select name="landing_template_id" class="mt-1" wire:model.defer="landingpage.landing_template_id">
                    <option value=""></option>
                    @foreach($this->templates as $template)
                        <option value="{{ $template->id }}" {{ $this->landingpage->template_id == $template->id ? 'selected' : '' }}>
                            {{ $template->name }}
                        </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="landingpage.landing_template_id" class="mt-2" />
            </div>
            <div class="col-span-3">
                <div class="flex flex-col">
                    <x-jet-label for="connection_ids" value="Connections" />
                    <p class="mr-2"><input type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="selectAll"><span class="ml-2 text-gray-700">Select All</span></p>
                    @foreach($this->connections as $connection)
                        <label class="inline-flex items-center mt-3">
                            <input name="list_ids" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="connection_ids" value="{{ $connection->id }}">
                            <span class="ml-2 text-gray-700">
                                {{ $connection->name }}
                                <span class="text-sm text-gray-500">{{ $connection->base_url }}</span>
                                
                            </span>
                        </label>
                    @endforeach
                </div>
                <x-jet-input-error for="connection_ids" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->landingpage->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
