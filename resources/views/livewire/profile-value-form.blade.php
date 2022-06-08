<body onload="myFunction()"></body>
<div>
    <x-form-section submit="submit" enctype="multipart/form-data" >
        <x-slot name="form">
            <div class="col-span-12">
                    @php
                   if(!empty($tokenprofiles->name)){
                        $url = $tokenprofiles->name;
                   }else{
                    $url = '?';
                    foreach($this->tokens as $token){
                     $url .= $token->name."=&";
                    }
                   } 
                     @endphp
                    <x-jet-label for="tokenprofiles" value="Profile" />
                    <x-jet-input id="tokenprofiles" name="tokenprofiles" type="textarea" class="deferdata mt-1 block w-full" wire:model.defer="tokenprofiles.name" />
                    <x-jet-input id="tokenprofiles" name="tokenprofiles" type="hidden" class="defer_one mt-1 block w-full" wire:ignore="tokenprofiles.name" value="{{substr($url,0,-1)}}"/>
                    <x-jet-input-error for="tokenprofiles.name" class="mt-2" />
                </div>
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
        list1[index].value=list[index].value;
    }
}
</script>