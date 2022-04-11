<x-form-section submit="submit">
    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="eventtemplate.name" />
            <x-jet-input-error for="eventtemplate.name" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="event_name" value="{{ __('Event Name') }}" />
            <x-jet-input id="event_name" type="text" class="mt-1 block w-full" wire:model.defer="eventtemplate.event_name" />
            <x-jet-input-error for="eventtemplate.event_name" class="mt-2" />
        </div>
        <div class="col-span-6">
            <x-jet-label for="event_location" value="{{ __('Event Location') }}" />
            <x-jet-input id="event_location" type="text" class="mt-1 block w-full" wire:model.defer="eventtemplate.event_location" />
            <x-jet-input-error for="eventtemplate.event_location" class="mt-2" />
        </div>
        <div class="col-span-6" wire:ignore>
            <x-jet-label for="event_location" value="{{ __('Content') }}" />
            <textarea id="spin_text" type="text" class="mt-1 block w-full" wire:model.defer="spin_text"></textarea>
            <x-jet-input-error for="spin_text" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('eventtemplate.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-50 transition ease-in-out duration-150" style="margin-right:5px">{{ __('Cancel') }}</a>
        <x-jet-button id="submit">
            {{ __(!$this->eventtemplate->exists ? 'Create' : 'Update') }}
        </x-jet-button>
    </x-slot>
</x-form-section>
<style>
    ul#listplace li {
    display:inline;
}
</style>
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>
<style type="text/css">
    body .shadow {
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%) !important;
        overflow: visible;
    }
    a:hover{
        text-decoration: none !important;
    }
    .placeholders{
        cursor: pointer;
        font-weight: 600;
        color: #6875f5;
    }
    .dropdown-toggle::after{
        display: none;
    }
    ul.note-dropdown-menu.dropdown-menu.note-check.dropdown-fontsize.show {
        overflow: scroll !important;
        height: 340px;
    }
    .modal-header .close{
        padding: 0px !important;
        margin: 0px !important;
    }
</style>

<script type="text/javascript">
$(document).ready(function() {
    $('#spin_text').summernote(
    { 
        fontSizes: ['8', '9', '10', '11', '12', '14', '18','20','22','24','26','28','30','32','34','36','38','40','42','44','46','48','50'],
        toolbar: [
            ['style', ['style']],
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link','picture','hr']],
            ['table', ['table']],
            ['view', ['fullscreen', 'codeview', 'help']],
          ],
        height: 500,
        focus: true,
        callbacks: {
            onChange: function(spin_text, $editable) {
                @this.set('spin_text', spin_text);
            }
        }
    });

});

</script>

