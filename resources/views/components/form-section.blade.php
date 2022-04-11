@props(['submit'])

<div {{ $attributes }}>
    @if(isset($title) || isset($description))
        <x-jet-section-title>
            <x-slot name="title">{{ $title ?? '' }}</x-slot>
            <x-slot name="description">{{ $description ?? '' }}</x-slot>
        </x-jet-section-title>
    @endif

    <div class="{{ isset($title) || isset($description) ? 'mt-5' : '' }}">
        <form wire:submit.prevent="{{ $submit }}">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        {{ $form }}
                    </div>
                </div>

                @if (isset($actions))
                    <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
