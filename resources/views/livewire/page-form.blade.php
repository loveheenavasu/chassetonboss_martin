<div>
    <x-form-section submit="submit">
        <x-slot name="form">
            <div class="col-span-2">
                <x-jet-label for="connection_id" value="{{ __('Connection') }}" />
                <x-select name="connection_id" class="mt-1" wire:model.defer="page.connection_id">
                    <option value=""></option>
                    @foreach($this->connections as $connection)
                        <option value="{{ $connection->id }}" {{ $this->page->connection_id == $connection->id ? 'selected' : '' }}>
                            {{ $connection->name . ' ' .  $connection->base_url }}
                        </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="page.connection_id" class="mt-2" />
            </div>

            <div class="col-span-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <x-jet-input id="slug" type="text" class="mt-1 block w-full" wire:model.defer="page.slug" />
                <x-jet-input-error for="page.slug" class="mt-2" />
            </div>

            <div class="col-span-6">
                <x-jet-label for="template_id" value="{{ __('Template') }}" />
                <x-select name="template_id" class="mt-1" wire:model.defer="page.template_id">
                    <option value=""></option>
                    @foreach($this->templates as $template)
                        <option value="{{ $template->id }}" {{ $this->page->template_id == $template->id ? 'selected' : '' }}>
                            {{ $template->name }}
                        </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="page.template_id" class="mt-2" />
            </div>

            <div class="col-span-2">
                <x-jet-label for="product" value="{{ __('Product') }}" />
                <x-jet-input id="product" type="text" class="mt-1 block w-full" wire:model.defer="page.product" />
                <x-jet-input-error for="page.product" class="mt-2" />
            </div>

            <div class="col-span-2">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="page.name" />
                <x-jet-input-error for="page.name" class="mt-2" />
            </div>

            <div class="col-span-2">
                <x-jet-label for="affiliate_link" value="{{ __('Affiliate Link') }}" />
                <x-jet-input id="affiliate_link" type="text" class="mt-1 block w-full" wire:model.defer="page.affiliate_link" />
                <x-jet-input-error for="page.affiliate_link" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                {{ __(!$this->page->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
