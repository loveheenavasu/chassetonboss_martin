<div>
    <x-form-section submit="submit" enctype="multipart/form-data" >
       
        <x-slot name="form">
             @foreach($this->tokens as $k => $token)
                <div class="col-span-2">
                    <x-jet-label for="{{$token->name}}_link" value="{{ __($token->name) }}" />
                    <x-jet-input id="json_data" name="json_data" type="text" class="mt-1 block w-full" wire:model="token_data.name.{{$token->name}}" value="{{$token->name}}" />
                </div>
            @endforeach

        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->tokenvalue->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
