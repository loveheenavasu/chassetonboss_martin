<div>
    <x-form-section submit="submit">
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="premiumtemplate.name" autofocus />
                <x-jet-input-error for="premiumtemplate.name" class="mt-2" />
            </div>
            <div class="col-span-6" wire:ignore>
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <textarea id="content" type="text" class="mt-1 block w-full" wire:model.defer="content"></textarea>
                <x-jet-input-error for="content" class="mt-2" />
                
                <p class="mt-3 text-sm text-gray-600">{{ __('Available placeholders') }}:</p>
                <ul>
                    <li>*PRODUCT NAME*</li>
                    <li>*HEADER TEXT*</li>
                    <li>*HERO TEXT1*</li>
                    <li>*HERO TEXT2*</li>
                    <li>*BUTTON TEXT*</li>
                    <li>*AFFLIATE LINK*</li>
                    <li>*HERO IMAGE*</li>
                    <li>*PRODUCT HEADER*</li>
                    <li>*PRODUCT IMAGE1*</li>
                    <li>*PRODUCT IMAGE2*</li>
                    <li>*PRODUCT IMAGE3*</li>
                    <li>*PRODUCT IMAGE1 LINK*</li>
                    <li>*PRODUCT IMAGE2 LINK*</li>
                    <li>*PRODUCT IMAGE3 LINK*</li>
                </ul>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-jet-label for="button_text" value="{{ __('Button Text') }}" />
                <x-jet-input id="button_text" type="text" class="mt-1 block w-full" wire:model.defer="premiumtemplate.button_text" autofocus />
                <x-jet-input-error for="premiumtemplate.button_text" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->premiumtemplate->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" />
<script type="text/javascript">
    $(document).ready(function() {
        $('#content').summernote(
        { 
            height: 500,
            focus: true,
            callbacks: {
                onChange: function(content, $editable) {
                       @this.set('content', content);
                    }
                }
        });
});
</script>
