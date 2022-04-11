<x-form-section submit="submit">
    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="eventlinktemplate.name" />
            <x-jet-input-error for="eventlinktemplate.name" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-jet-label for="name" value="{{ __('Links') }}" />
            <textarea  class="form-input rounded-md shadow-sm mt-1 block w-full" rows="6" wire:model.defer="eventlinktemplate.links"></textarea>
            <x-jet-input-error for="eventlinktemplate.links" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-jet-label for="template" value="{{ __('Content') }}" />
            <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="8" wire:model.defer="eventlinktemplate.template" >
            </textarea> 
        </div>               
        
    </x-slot>
    
    <x-slot name="actions">
        <a href="{{ route('eventlinktemplate.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-50 transition ease-in-out duration-150" style="margin-right:5px">{{ __('Cancel') }}</a>
        <x-jet-button>
            {{ __(!$this->eventlinktemplate->exists ? 'Create' : 'Update') }}
        </x-jet-button>
    </x-slot>
</x-form-section>

<style type="text/css">
body .shadow {
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%) !important;
    overflow: visible;
}
.dropdown.bootstrap-select.show-tick.form-input.rounded-md.shadow-sm.block.w-full.mt-1 {
    width: 100%;
}

.rounded-md.shadow-sm .dropdown-toggle .filter-option:focus-visible{
    outline: none !important;
}

button.btn.dropdown-toggle:focus {
    outline: none !important;
}

.shadow .pointer-events-none {
    display: none;
}
.bs-ok-default.check-mark::before, ::after {
    border-color: #6875f5;
}
a:hover{
    text-decoration: none !important;
}

</style>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css"/>

<script type="text/javascript">
  



</script>


