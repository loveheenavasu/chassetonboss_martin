<div>
    <x-jet-form-section submit="import">
        <x-slot name="title">
            Import emails to list
        </x-slot>

        <x-slot name="description">
            All duplicated records will be skipped.
        </x-slot>
        
        <x-slot name="form">
            <div class="col-span-6" wire:loading wire:target="import">
                <div x-show="isUploading">
                    <div class="loader"></div>
                    Validator is in progress....
                </div>
            </div>
            <div class="col-span-6">
                <x-jet-label for="file" value="{{ __('File') }}" />
                <input id="file" type="file" class="mt-1 block w-full" wire:model="file">
                <x-jet-input-error for="file" class="mt-2" />
                <p class="text-gray-500"><small>Allowed extensions: .txt .csv</small></p>
            </div>
            @if($this->uploaded)
                <div class="col-span-6">
                    <div class="groupcheck">
                    <input name="allmail" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="allmail" wire:model="leadvalidator.allmail">
                    <span class="ml-2 text-gray-700">Don't run validate</span>
                    <x-jet-input-error for="leadvalidator.allmail" class="mt-2" />
                    
                    <input name="onlyhttp" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="onlyhttp" wire:model="leadvalidator.onlyhttp">
                    <span class="ml-2 text-gray-700">Http Only</span>
                    <x-jet-input-error for="leadvalidator.onlyhttp" class="mt-2" />
                    
                    <input name="profession" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="profession" wire:model="leadvalidator.profession">
                    <span class="ml-2 text-gray-700">Profession</span>
                    <x-jet-input-error for="leadvalidator.profession" class="mt-2" />
                    </div>
                </div>
            @if($this->leadvalidator->profession == true)
                <div class="col-span-6">
                    <x-jet-label for="separator" value="{{ __('Profession Lists') }}" class="block font-large text-lg text-gray-700"/>
                    @foreach($this->professions as $profession)
                    <input name="professionlist" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="professionlist" value="{{$profession->id}}">
                    <span class="ml-2 text-gray-700 text-bg">{{$profession->name}}</span>
                    @endforeach
                </div>
            @endif
            <div class="col-span-6"><p>This file contains {{$this->countallCorpEmail}} Corporated emails and {{$this->countallWebEmail}} Webmails</p>
                <div class="groupcheck">
                    <input name="webmail" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="webmail" wire:model="leadvalidator.webmail">
                    <span class="ml-2 text-gray-700">Validate Web Emails</span>
                    <x-jet-input-error for="leadvalidator.webmail" class="mt-2" />
                </div>
                <div class="groupcheck">
                    <input name="corpmail" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="corpmail" wire:model="leadvalidator.corpmail">
                    <span class="ml-2 text-gray-700">Validate Corporated Emails</span>
                    <x-jet-input-error for="leadvalidator.corpmail" class="mt-2" />
                </div>
            </div> 
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
                       
                        <option value="{{ $key }}" selected="selected">{{ $value }}</option>
                  
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
            <div class="col-span-6">
                <x-jet-label for="notes" value="{{ __('Notes') }}" />
                <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="3" wire:model="leadvalidator.notes" id="notes"></textarea>
                <x-jet-input-error for="leadvalidator.notes" class="mt-2" />
                <input type="hidden" name="" value="{{$this->leadvalidator->id}}" id="list_id">
            </div>
            <x-jet-button type="button" class="mr-3" id="testing">
                {{ __('Update') }}
            </x-jet-button>
        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="imported">
                {{ __('Imported.') }}
            </x-jet-action-message>
            <x-jet-button type="button" class="cancel-file" style="margin-right:5px">
                {{ __('Cancel') }}
            </x-jet-button>
            <x-jet-button :disabled="!$this->uploaded">
               {{ __('Validator') }}
            </x-jet-button>
           
        </x-slot>
    </x-jet-form-section>
</div>
<style>
.loader {
    border: 6px solid #f3f3f3;
    border-radius: 50%;
    border-top: 6px solid #252f3f;
    width: 35px;
    height: 35px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#testing').on('click',function(){
            $('.loading').show();
            var notes = $('#notes').val();
            var list_id = $("#list_id").val();
            $.ajax(
            {
                url: "/leadvalidatornotes",
                data: {notes:notes,id:list_id}, 
                success: function(result){
                    window.location.href = result;
                    alert("Notes Updated Sucessfully!");
                }
            });
        });
    });
</script>