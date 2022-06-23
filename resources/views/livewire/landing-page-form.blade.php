<div>
    <x-form-section submit="submit">
        <x-slot name="form">
            <div class="col-span-3">
                <x-jet-label for="connection_id" value="{{ __('Connection *') }}" />
                <x-select name="connection_id" class="mt-1" wire:model.defer="landingpage.connection_id">
                    <option value=""></option>
                    @foreach($this->connections as $connection)
                        <option value="{{ $connection->id }}" {{ $this->landingpage->connection_id == $connection->id ? 'selected' : '' }}>
                            {{ $connection->name . ' ' .  $connection->base_url }}
                        </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="landingpage.connection_id" class="mt-2" />
            </div>
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
            <div class="col-span-6">
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
            @php

            if($this->landingpage->tools !="landingpage"){
                @endphp

            <div class="col-span-2">
                <x-jet-label for="product" value="{{ __('Product') }}" />
                <x-jet-input id="product" type="text" class="mt-1 block w-full" wire:model.defer="landingpage.product" />
                <x-jet-input-error for="landingpage.product" class="mt-2" />
            </div>

            <div class="col-span-2">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="landingpage.name" />
                <x-jet-input-error for="landingpage.name" class="mt-2" />
            </div>

            <div class="col-span-2">
                <x-jet-label for="affiliate_link" value="{{ __('Affiliate Link ') }}" />
                <x-jet-input id="affiliate_link" type="text" class="mt-1 block w-full" wire:model.defer="landingpage.affiliate_link" />
                <x-jet-input-error for="landingpage.affiliate_link" class="mt-2" />
            </div>
            @php
                }
            @endphp
             <input type="hidden" name="token_val"value="{{ __('test') }}" >
            @php
                $data_array = [];
                $json_data_values = json_decode($landingpage->json_data);
            @endphp
            <!-- @foreach($this->tokens as $k => $token)
                <div class="col-span-2">
                    <x-jet-label for="{{$token->name}}_link" value="{{ __($token->name) }}" />
                    <x-jet-input id="json_data" name="json_data" type="text" class="mt-1 block w-full" wire:model.defer="json_data.{{str_replace(' ' , '_',$token->name)}}"  />
                </div>
            @endforeach -->
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->landingpage->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
