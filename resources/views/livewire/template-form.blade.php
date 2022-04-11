<div>
    <x-form-section submit="submit">
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="template.name" autofocus />
                <x-jet-input-error for="template.name" class="mt-2" />
            </div>
            <div class="col-span-6" wire:ignore>
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <div
                    style="height: 400px"
                    x-data
                    x-ref="quillEditor"
                    x-init="
                        Size = Quill.import('attributors/style/size');
                        Size.whitelist = window.sizes;
                        Quill.register(Size, true);

                         quill = new Quill($refs.quillEditor, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                     [{ 'size': window.sizes }],
                                     ['bold', 'italic', 'underline', 'strike'],
                                     [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                     ['clean']
                                ]
                            }
                        });
                         quill.on('text-change', function () {
                        @this.set('template.content', quill.root.innerHTML)
                     });
"
                    id="quillEditor"
                >
                    {!! $this->template->content !!}
                </div>
                <x-jet-input-error for="template.content" class="mt-2" />
                <p class="mt-3 text-sm text-gray-600">{{ __('Available placeholders') }}:</p>
                <ul>
                    <li>*PRODUCT*</li>
                    <li>*NAME*</li>
                </ul>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-jet-label for="button_text" value="{{ __('Button Text') }}" />
                <x-jet-input id="button_text" type="text" class="mt-1 block w-full" wire:model.defer="template.button_text" autofocus />
                <x-jet-input-error for="template.button_text" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->template->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        @foreach($this->sizes as $size)
        .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="{{ $size }}"]::before {
            content: '{{ $size }}';
        {{--font-size: {{ $size }} !important;--}}
        }
        @endforeach
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        window.sizes = @json($this->sizes)
    </script>
@endpush
