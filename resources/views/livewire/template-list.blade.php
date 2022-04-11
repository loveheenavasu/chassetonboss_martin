<div>
    <x-action-section>
        <x-slot name="title">
            {{ __('Manage Templates') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if($this->templates->isNotEmpty())
                    @foreach($this->templates as $template)
                        <div class="flex items-center justify-between">
                            <div>
                                {{ $template->name }}
                            </div>

                            <div class="flex items-center">
                                <a href="{{ route('templates.show', ['template' => $template->id]) }}" target="_blank" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Preview') }}
                                </a>

                                <a href="{{ route('templates.edit', ['template' => $template->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Edit') }}
                                </a>

                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmTemplateDeletion({{ $template->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                     @if($this->templates->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->templates->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No templates yet.') }}</div>
                @endif

            </div>
        </x-slot>
    </x-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingTemplateDeletion">
        <x-slot name="title">
            {{ __('Delete Template') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this template?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingTemplateDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteTemplate" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
