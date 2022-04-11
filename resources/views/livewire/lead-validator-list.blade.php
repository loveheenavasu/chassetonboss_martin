<div>
    <x-jet-action-section>
        <x-slot name="title">
            {{ __('Manage Lists') }}
        </x-slot>

        <x-slot name="description">
            Contains sets of emails.
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @if(session()->has('message'))
                    <div id="successMessage" class="alert alert-success"> 
                     <button type="button" class="close" data-dismiss="alert">×</button> 
                        {{ session('message') }} 
                    </div> 
                @endif
                @if ($this->leadvalidators->isNotEmpty())
                    @foreach ($this->leadvalidators as $lvalid)
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('leadvalidator.show', ['leadvalidator' => $lvalid->id]) }}" target="_blank">{{ $lvalid->name }}</a>
                                <small class="text-gray-400 ml-2"></small>
                            </div>

                            <div class="flex items-center">


                                @php 
                                $name=str_replace(' ', '', strtolower($lvalid->name));
                                $name = str_replace(array(':', '\\', '/', '*','-','_'), '', $name);
                              
                                @endphp

                                @if(file_exists($name.'-allwebemail.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-allwebemail.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Web Email Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-allcorpemail.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-allcorpemail.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Corp Email Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-valid.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-valid.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Valid CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-invalid.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-invalid.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Invalid CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-unknown.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-unknown.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Unknown CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-valid-web.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-valid-web.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Valid Web CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-invalid-web.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-invalid-web.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('InValid Web CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-unknown-web.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-unknown-web.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Unknown Web CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-valid-corp.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-valid-corp.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Valid Corp CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-invalid-corp.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-invalid-corp.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('InValid Corp CSV Download') }}
                                        </a> 
                                @endif
                                @if(file_exists($name.'-unknown-corp.csv')) 
                                        <a href="{{ url('') }}/{{$name}}-unknown-corp.csv" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                            {{ __('Unknown Corp CSV Download') }}
                                        </a> 
                                @endif
                                <a href="{{ route('leadvalidator.show', ['leadvalidator' => $lvalid->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Details') }}
                                </a>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmingLeadvalidatorDeletion({{ $lvalid->id}})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->leadvalidators->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->leadvalidators->fragment('leadvalidator')->links() }}
                        </div>
                    @endif
                    @else
                        <div>{{ __('No lists yet.') }}</div>
                    @endif
            </div>
        </x-slot>
    </x-jet-action-section>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingLeadvalidatorDeletion">
        <x-slot name="title">
            {{ __('Delete Lead Validator') }}
        </x-slot>

        <x-slot name="content">
                @if($this->leadvalidatorBeingDeleted)
                    {{ __('Are you sure you want to delete lead validator :name?', ['name' => $this->leadvalidatorBeingDeleted->name]) }}
                @endif
        </x-slot>

        <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingLeadvalidatorDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteLeadvalidator" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
<style type="text/css">
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
    padding-right: 4rem;
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}

.alert-success button.close {
    position: absolute;
    top: 0;
    right: 0;
    padding: .75rem 1.25rem;
    color: inherit;
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function() {
            $('#successMessage').fadeOut('fast');
        }, 2000);
    });
</script>
