<div>
    <x-jet-form-section submit="process">
        <x-slot name="title">
            Files Cutter
        </x-slot>

        <x-slot name="description">
            <ul class="text-sm text-gray-600 list-inside list-disc">
                <li>Merge multiple files with emails to one.</li>
                <li>Cut merged file to chunks.</li>
                <li>Add column headers.</li>
                <li>Add second column values.</li>
            </ul>
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="files" value="{{ __('Files') }}" />
                <input id="file" type="file" class="mt-1 block w-full" wire:model="files" multiple>
                <x-jet-input-error for="files" class="mt-2" />
                <x-jet-input-error for="files.*" class="mt-2" />
                <p class="text-gray-500"><small>Allowed extensions: .txt .csv</small></p>
                <p class="text-gray-500"><small>Max files size: 200mb</small></p>
            </div>

            @if(count($emails))
                <div class="col-span-6">
                    <div>{{ count($emails) }} total emails.</div>
                    @if($skippedCount)
                        <div>{{ $skippedCount }} skipped lines.</div>
                    @endif
                </div>
            @endif

            <div class="col-span-4">
                <x-jet-label for="filename" value="{{ __('Filename') }}" />
                <x-jet-input id="filename" type="text" class="mt-1 block w-full" wire:model.defer="filename" />
                <x-jet-input-error for="filename" class="mt-2" />
            </div>
             <input type="hidden" name="" value="{{$this->seperator_type}}" id="seperator_type">
            <div class="col-span-2">
                <x-jet-label for="separator" value="{{ __('Csv Separator') }}" />
                <x-select id="separator" class="mt-1 block w-full" wire:model.defer="separator">
                    <pre>
                    @foreach($this->separators as $key => $value)
                    
                        @if(trim($key) == $this->seperator_type)
                       
                        <option value="{{ $key }}" selected="selected">{{ $value }}</option>
                  
                        @endif
                    @endforeach
                </x-select>
                <x-jet-input-error for="separator" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="column_header" value="{{ __('Column Header') }}" />
                <x-jet-input id="column_header" type="text" class="mt-1 block w-full" wire:model.defer="column_header" />
                <x-jet-input-error for="column_header" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="col_loc" value="{{ __('Column') }}" />
                <x-select id="col_loc" class="mt-1 block w-full" wire:model.defer="col_loc">
                    @foreach($this->col as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="col_loc" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="column_value" value="{{ __('Column Value') }}" />
                <x-jet-input id="column_value" type="text" class="mt-1 block w-full" wire:model.defer="column_value" />
                <x-jet-input-error for="column_value" class="mt-2" />
                <p class="text-sm text-gray-500">Use <code class="underline">:index:</code> placeholder to inject chunk number.</p>
            </div>

            <div class="col-span-2">
                <x-jet-label for="crop_mode" value="{{ __('Cropping mode') }}" />
                <x-select id="crop_mode" class="mt-1 block w-full" wire:model="crop_mode">
                    @foreach($this->cropModes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="crop_mode" class="mt-2" />
            </div>

            <div class="col-span-4">
                @if ($crop_mode === 'emails_per_chunk')
                    <x-jet-label for="emails_per_chunk" value="{{ __('Emails per chunk') }}" />
                    <x-jet-input id="emails_per_chunk" type="number" min="1" class="mt-1 block w-full" wire:model.defer="emails_per_chunk" />
                    <x-jet-input-error for="emails_per_chunk" class="mt-2" />
                @elseif($crop_mode === 'chunks_count')
                    <x-jet-label for="chunks_count" value="{{ __('Chunks count') }}" />
                    <x-jet-input id="chunks_count" type="number" min="1" class="mt-1 block w-full" wire:model.defer="chunks_count" />
                    <x-jet-input-error for="chunks_count" class="mt-2" />
                @endif
            </div>

            @if($processed)
                <div class="col-span-6">
                    <div>{{ $this->calculatedEmailsPerChunk() }} emails per chunk.</div>
                    <div>{{ $this->calculatedChunksCount() }} total chunks.</div>
                </div>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="processed">
                {{ __('Processed.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __('Process') }}
            </x-jet-button>

            <x-jet-button type="button" class="mr-2" wire:click.prevent="downloadMergedFile" :disabled="!$processed" class="ml-2">
                {{ __('Download merged file') }}
            </x-jet-button>

            <x-jet-button type="button" wire:click.prevent="downloadChunksArchive" :disabled="!$processed" class="ml-2">
                {{ __('Download chunks zip') }}
            </x-jet-button>
        </x-slot>

    </x-jet-form-section>
</div>
