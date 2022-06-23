<body onload="myFunction()"></body>

<body onload="myFunctiontwo()"></body>
<div>
    <x-form-section submit="submit" enctype="multipart/form-data">
        <x-slot name="form">

            <div class="col-span-12 ">
            </div>
            <div class="col-span-12 ">
                <x-jet-label for="tokenprofiles" value="Profile Name" />
                <x-jet-input id="tokenprofiles" name="profile_name" type="textarea" class="deferdata mt-1 block w-full"
                    wire:model.defer="token_data_profile.profile_name" />
                <x-jet-input id="tokenprofiles" name="profile_name" type="hidden" class="defer_one mt-1 block w-full"
                    wire:ignore="token_data_profile.profile_name" value="{{ $tokenprofiles->profile_name }}" />
            </div>

            @php
                $token_data_values = json_decode($this->tokenprofiles->token_data,true);
            @endphp
           
                @foreach ($this->tokens as $k => $token)
                    @php
                        $k_name = $token->name;    
                    @endphp
                    <div class="col-span-2">
                        <x-jet-label for="{{ $token->name }}_link" value="{{ __($token->name) }}" />
                        <x-jet-input id="token_datas" name="token_data" type="text" class="deferdata mt-1 block w-full" wire:model.defer="token_data_profile.token_data.{{ $token->name }}" />
                        <x-jet-input id="token_datas" name="token_data" type="hidden" class="defer_one mt-1 block w-full" wire:ignore="token_data_profile.token_data.{{ $token->name }}" value="{{ $token_data_values ? $token_data_values['token_data'][$token->name] : ''}}"/>
                    </div>
                @endforeach
        </x-slot>
        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->tokenprofiles->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
<script type="text/javascript">
    function myFunction() {
        var list, index;
        list = document.getElementsByClassName("defer_one");
        list1 = document.getElementsByClassName("deferdata");
        for (index = 0; index < list.length; ++index) {
            list1[index].value = list[index].value;
        }
    }
</script>
