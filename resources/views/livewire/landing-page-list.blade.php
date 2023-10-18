<div>
    <x-action-section>
        <x-slot name="content">
            <div class="space-y-6">
                @if($this->landingpages->isNotEmpty())
                    @foreach($this->landingpages as $landingpage)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('landingpages.edit', ['landingpage' => $landingpage->id]) }}">
                                    {{ $landingpage->slug }}
                                </a>
                            </div>

                            <div class="flex items-center">
                                @if($this->hasLandingpages($landingpage))
                                    <button wire:click="downloadLinksFile('{{ $landingpage->id }}')" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                        {{ __('Download urls') }}
                                    </button>

                                    <a href="{{ $landingpage->full_url }}" data-copy="{{ $landingpage->copiedValue() }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                        {{ __('Copy urls') }}
                                    </a>

                                    <a href="{{ $landingpage->full_url }}" data-copy="{{ '{'.$landingpage->copiedValueSpintax().'}' }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                        {{ __('Copy Spintax') }}
                                    </a>

                                    <!-- <button class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none" data-copy="{{$landingpage->copiedValue()}}" wire:click="QueryParams({{ $landingpage->id }})">Adds Parameters</button> -->
                                    
                                @endif

                                <a href="{{ route('landingpages.edit', ['landingpage' => $landingpage->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Edit') }}
                                </a>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmLandingPageDeletion({{ $landingpage->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->landingpages->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->landingpages->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No Landingpages yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!--< x-jet-dialog-modal wire:model="queryParamsValues" maxWidth="1100px" class="flex items-center my-custom-class">
    
        <x-slot name="title">
            {{ __('Add Query Parameters') }}
        </x-slot>
        <x-slot name="content">
        <div class="col-span-3">
            <x-jet-input id="queryparams" name="queryparams" type="text" class="mt-1 block w-full" wire:model="queryparams"/>
            <x-jet-input-error for="queryparams" class="mt-2" />
        </div>
        </x-slot>
        <x-slot name="footer">

            <x-jet-secondary-button wire:click="addQueryParamsValue()" >
                {{ __('Add Parameters') }}
            </x-jet-secondary-button>
            
            @if (session()->has('message'))
                <div class="alert alert-warning text-red-500" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            
            @if($flag == 1)
                <x-jet-secondary-button wire:click="downloadParamsUrls()" wire:loading.attr="disabled">
                    {{ __('Download Urls With Parameters') }}
                </x-jet-secondary-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal> -->

    

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingLandingpageDeletion">
        <x-slot name="title">
            {{ __('Delete Syndication') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this syndication?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingLandingpageDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteLandingpage" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
