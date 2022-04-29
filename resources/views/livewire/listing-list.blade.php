<div>
    <div class="md:grid md:gap-6">
        <x-jet-section-title>
                <x-slot name="title">{{ __('Manage Lists') }}</x-slot>
                <x-slot name="description">Contains sets of emails.</x-slot>
        </x-jet-section-title>
        <div class="mt-5 md:mt-0">
            <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
                <div class="space-y-6">
                    <div class="flex items-center">
                    <button type="button" class="btn btn-warning delete_all_list inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 mr-4" data-url="">Delete All Selected</button>
                    <button  type="button" class="btn btn-warning px-4 py-2 bg-gray-800 text-left text-xs font-medium text-white uppercase tracking-wider border border-transparent rounded-md " id='list_check_all'  data-checked='false'>check/uncheck all</button>
                    </div>

                    @if ($this->listings->isNotEmpty())
                    @foreach ($this->listings as $listing)
                        <div class="flex items-center justify-between">
                            <div>
                               <input type="checkbox" class="sub_chk" data-id="{{ $listing->id }}" data-value="{{ $listing->name }}">
                                <a href="{{ route('listings.show', ['listing' => $listing->id]) }}" target="_blank">{{ $listing->name }}</a>
                                <small class="text-gray-400 ml-2">Emails total: {{ $listing->emails()->count() }}, in pool: {{ $listing->emails()->wherePivot('in_pool', true)->count() }}</small>
                            </div>

                            <div class="flex items-center">
                                @isset($listing->valid_emails)
                                <a style="color:green;" href="{{ route('listings.show', ['listing' => $listing->id]) }}" target="_blank">
                                    {{number_format((int)$listing->valid_emails/$listing->emails()->count()*100,2, '.', '')."%"}}</a>
                                @else
                                <a style="color:green;" href="{{ route('listings.show', ['listing' => $listing->id]) }}" target="_blank">
                                    {{"0.00%"}}</a>
                                @endif


                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none" wire:click="exportNotInPollList({{ $listing->id}})">
                                    {{ __('Export Pool') }}
                                </button>

                                <button class="cursor-pointer ml-6 text-sm text-gray-500 focus:outline-none" wire:click="exportInPollList({{ $listing->id}})">
                                    {{ __('Export Used') }}
                                </button>
                                
                                <a href="{{ route('listings.show', ['listing' => $listing->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                    {{ __('Details') }}
                                </a>
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmingListingDeletion({{ $listing->id}})">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if($this->listings->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->listings->fragment('listings')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No lists yet.') }}</div>
                @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingListingDeletion">
        <x-slot name="title">
            {{ __('Delete Listing') }}
        </x-slot>

        <x-slot name="content">
            @if($this->ruleRunning == true)
                {{ __("List Can't be deleteable. This list is used in current running rule. So you can not delete it.") }}
            @endif
            @if($this->listingBeingDeleted && $this->ruleRunning == false)
                {{ __('Are you sure you want to delete Listing :name?', ['name' => $this->listingBeingDeleted->name]) }}
            @endif
        </x-slot>

        <x-slot name="footer">
            @if($this->ruleRunning == true)
                <x-jet-secondary-button wire:click="$toggle('confirmingListingDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>
            @endif
            @if($this->ruleRunning == false)
                <x-jet-secondary-button wire:click="$toggle('confirmingListingDeletion')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteList" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-jet-danger-button>
            @endif

        </x-slot>
    </x-jet-confirmation-modal>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#list_check_all').on('click',function () {
            var d = $(this).data(); 
            $(':checkbox').prop('checked', !d.checked); 
            d.checked = !d.checked;
        });
        $('.delete_all_list').on('click',function (e) {
            var allVals = [];
            var allValsName = [];
            $('.sub_chk:checked').each(function () {
                allVals.push($(this).attr('data-id'));
                allValsName.push($(this).attr('data-value'))
            });
            if (allVals.length <= 0) {
                alert("Please select row.");
            }else {

                //var join_selected_values = allVals.join("");
                var join_selected_values_name = '<ul class="myList"><li class="ui-menu-item" role="menuitem">' + allValsName.join('</li><li>') + '</li></ul>';

                swal({
                      title: `Are you sure you want to delete the below list?`,
                      content: {
                          element: 'ul',
                          attributes: {
                            innerHTML: `${join_selected_values_name}`,
                          },
                        },
                      text: "If you delete this, it will be gone forever.",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                  })
                  .then((willDelete) => {
                    if (willDelete) {
                      $.ajax({
                            url: "/deletelist",
                            type:"get",
                            dataType : 'json',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data:'ids='+allVals,
                            success:function (data) {
                                var list_status = []
                                $.each(data, function(i, o) {
                                    list_status.push(data[i].status);
                                });
                                
                                if($.inArray('running', list_status) !== -1){

                                    swal("One of the list you have selected is currently used in the running rule. So you can not delete it.")
                                    .then((value) => {
                                      window.location.reload();
                                    });   
                                }
                                else{
                                    window.location.reload();
                                }   
                            },
                            error: function (data) {
                                console.log(data.responseText);
                            }
                        });
                    }
                });
            }
        });
       
    });
</script>
