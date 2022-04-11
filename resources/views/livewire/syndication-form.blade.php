<x-form-section submit="submit">
    <x-slot name="title">
        Syndication
    </x-slot>
    <x-slot name="form">
        <div class="col-span-3">
            <x-jet-label for="content_id" value="{{ __('Content') }}" />
            <x-select name="content_id" class="mt-1" wire:model.defer="syndication.content_id">
                <option value=""></option>
                @foreach($this->contents as $content)
                    <option value="{{ $content->id }}">
                        {{ $content->name }}
                    </option>
                @endforeach
            </x-select>
            <x-jet-input-error for="syndication.content_id" class="mt-2" />
        </div>

        <div class="col-span-3">
            <x-jet-label for="slug" value="{{ __('Slug') }}" />
            <x-jet-input id="slug" type="text" class="mt-1 block w-full" wire:model.defer="syndication.slug" />
            <x-jet-input-error for="syndication.slug" class="mt-2" />
        </div>

        <div class="col-span-3">
            <div class="flex flex-col">
                <x-jet-label for="connection_ids" value="Connections" />
                <p class="mr-2"><input type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="selectAll"><span class="ml-2 text-gray-700">Select All</span></p>
                @foreach($this->connections as $connection)
                    <label class="inline-flex items-center mt-3">
                        <input name="list_ids" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="connection_ids" value="{{ $connection->id }}">
                        <span class="ml-2 text-gray-700">
                            {{ $connection->name }}
                            <span class="text-sm text-gray-500">{{ $connection->base_url }}</span>
                            @if($this->wasConnectionDeployed($connection))
                                <span class="text-green-500 text-sm">Deployed</span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
            <x-jet-input-error for="connection_ids" class="mt-2" />
        </div>
        @if (session()->has('message'))
            <div class="col-span-3">
                <p class="text-sm text-red-600 mt-2">{{ session('message') }}</p>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Perform') }}
        </x-jet-button>
    </x-slot>
</x-form-section>
