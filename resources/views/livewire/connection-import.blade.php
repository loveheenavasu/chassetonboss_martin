<div>
    <x-jet-form-section submit="import">
        <x-slot name="title">
            Import emails to list
        </x-slot>

        <x-slot name="description">
            All duplicated records will be skipped.
        </x-slot>
        
        <x-slot name="form">
            
            <div class="col-span-6">
                <x-jet-label for="file" value="{{ __('File') }}" />
                <input id="file" type="file" class="mt-1 block w-full" wire:model="file">
                <x-jet-input-error for="file" class="mt-2" />
                <p class="text-gray-500"><small>Allowed extensions: .txt .csv</small></p>
            </div>
            @if($this->uploaded)
                <div class="col-span-6" id="row-count">
                    {{ $this->rowsCount }} rows in file.
                </div>
    
           <input type="hidden" name="" value="{{$this->seperator_type}}" id="seperator_type">
           <div class="col-span-2">
                <x-jet-label for="separator" value="{{ __('Csv Separator') }}" />
                <x-select id="separator" class="mt-1 block w-full" wire:model.defer="separator">
                    <pre>
                    @foreach($this->separators as $key => $value)
                    
                        @if(trim($key) == $this->seperator_type)
                       
                        <option value="{{ $key }}" selected="selected">{{ $value }}
                        </option>
                  
                        @endif
                    @endforeach
                </x-select>
                <x-jet-input-error for="separator" class="mt-2" />
            </div>
             @endif
            @if($this->columnsCount > 0)
                <div class="col-span-6" id="row-data">
                    <div class="grid grid-cols-2 gap-6">
                        <?php $index = 0;?>
                        @foreach($this->extracolumn() as $colkey => $colvalue)
                            <div class="col-span-1">
                                <x-jet-label value="{{ $colvalue }}" />
                                <x-select class="mt-1" wire:model="columns.{{ $index }}">
                                    <option value=""></option>
                                    @foreach($this->columnValues() as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                            <?php $index++;?>
                        @endforeach
                    </div>
                    <x-jet-input-error for="columns" class="mt-2" />
                </div>
            @endif
            
            
        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="imported">
                {{ __('Imported.') }}
            </x-jet-action-message>
            <x-jet-button type="button" class="cancel-file" style="margin-right:5px">
                {{ __('Cancel') }}
            </x-jet-button>
            <x-jet-button :disabled="!$this->uploaded">
                {{ __('Import') }}
            </x-jet-button>
           
        </x-slot>
    </x-jet-form-section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#testing').on('click',function(){
            $('.loading').show();
            var notes = $('#notes').val();
            var list_id = $("#list_id").val();
            $.ajax(
            {
                url: "/notes",
                data: {notes:notes,id:list_id}, 
                success: function(result){
                    window.location.href = result;
                    alert("Notes Updated Sucessfully!");
                }
            });
        });
    });
</script>