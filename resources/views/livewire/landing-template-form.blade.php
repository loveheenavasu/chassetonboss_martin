<div>
    <x-form-section submit="submit" class="landing-template-form" wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))">
      <x-slot name="form">

        <div class="col-span-12 sm:col-span-12">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="landingtemplate.name" autofocus />
            <x-jet-input-error for="landingtemplate.name" class="mt-2" />
        </div>

        <div class="col-span-12 sm:col-span-12">
            <x-jet-label for="content" value="{{ __('Editor') }}" />
            <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="15" wire:model.defer="landingtemplate.content" spellcheck="true"></textarea>
            <x-jet-input-error for="landingtemplate.content" class="mt-2" />
            <p class="mt-3 text-sm text-gray-600">{{ __('Available placeholders') }}:</p>
            <ul>
              @foreach($this->Tokennn as $single)
                <li>{{$single->name}}</li>
                @endforeach
            </ul>
        </div>

      </x-slot>

        <x-slot name="actions" >
            <x-jet-button id="actions">
                {{ __(!$this->landingtemplate->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
