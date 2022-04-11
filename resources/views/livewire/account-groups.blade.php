<div>
    <x-form-section submit="submit">
        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="group.name"/>
                <x-jet-input-error for="group.name" class="mt-2" />
            </div>
                <div class="col-span-6">
                    <x-jet-label for="locationUsers" value="{{ __('Gmail Connections') }}" />
                    @foreach($this->emails as $email)
                        <label class="inline-flex items-center mt-3" style="display: flex;">
                            <input name="gmails" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="gmails" value="{{ $email->id }}">
                            <span class="ml-2 text-gray-700">{{ $email->email_id }}</span>
                        </label>
                    @endforeach
                    
                    <x-jet-input-error for="gmails" class="mt-2" />
                </div>
                @if($this->emails->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $this->emails->fragment('')->links() }}
                    </div>
                @endif
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
               {{ __(!$this->group->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select').selectpicker({
            dropupAuto: false,
            refresh:true,
        });
    });

</script>
