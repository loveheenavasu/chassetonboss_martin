<div>
    <x-jet-action-section>
        <x-slot name="title">
            {{ __('Files Cutter Logs') }}
        </x-slot>

        <x-slot name="description">
            History of previous cuts.
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if ($this->logs->isNotEmpty())
                    @foreach ($this->logs as $log)
                        <div class="flex items-center justify-between">
                            <div>
                                {{ $log->merged_filename }}
                                <small class="text-gray-500 font-sm">({{ $log->created_at->format('d.m.Y H:i') }})</small>
                            </div>

                            <div class="flex items-center">
                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none download_msg" wire:click.prevent="downloadMergedFile({{ $log->id }})" >
                                    {{ __('Download merged file') }}
                                </button>
                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none download_msg" wire:click.prevent="downloadChunksArchive({{ $log->id }})" >
                                    {{ __('Download chunks zip') }}
                                </button>
                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none" wire:click.prevent="confirmCutterLogsDeletion({{ $log->id }})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->logs->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->logs->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No cuts yet.') }}</div>
                @endif
            </div>
        </x-slot>
    </x-jet-action-section>
    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingConnectionDeletion">
        <x-slot name="title">
            {{ __('Delete Cutter Log') }}
        </x-slot>

        <x-slot name="content">
           
                {{ __('Are you sure you want to delete this cutter log ?') }}
        
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingConnectionDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteCutterLogs" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
    <!-- Fiile not availble modal -->
    <x-jet-confirmation-modal wire:model="fileNotAvaiale">
        <x-slot name="title">
            {{ __('Info') }}
        </x-slot>

        <x-slot name="content">
           
                {{ __('This file is not avaiable') }}
        
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('fileNotAvaiale')" wire:loading.attr="disabled" id="nver_mind">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

           
        </x-slot>
    </x-jet-confirmation-modal>
    <!-- Fiile not availble modal -->
    <!-- <x-jet-confirmation-modal wire:model="waitDownloaded" style="">
        <x-slot name="title">
            {{ __('Info') }}
        </x-slot>

        <x-slot name="content">
           
                {{ __('Your file is Downloading please wait..') }}
        
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('fileNotAvaiale')" wire:loading.attr="disabled" id="nver_mind">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

           
        </x-slot>
    </x-jet-confirmation-modal> -->
    <p id= "msgg_s" style="color: green;display:none;color: green;
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translate(-10%, 400%);
    background: #fff;
    text-align: center;
    width: 32%;
    z-index: 9;
    box-shadow: 0 0 10px #ccc;
    padding: 40px 10px;
    font-size: 21px;">Your file is Downloading please wait</p>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
       $('.download_msg').click(function(){
        $('#msgg_s').show();
         setTimeout(function() { $("#msgg_s").hide(); }, 10000);//
       })
    })
</script>