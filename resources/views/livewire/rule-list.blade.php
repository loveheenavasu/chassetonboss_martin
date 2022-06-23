
<div>
    <div class="md:grid md:gap-6">
        <x-jet-section-title>
                <x-slot name="title">{{ __('Manage Rules') }}</x-slot>
                <x-slot name="description">Configure rules to automate emails export.</x-slot>
        </x-jet-section-title>
            <div class="flex">
                <button type="button" class="btn btn-warning delete_all_rule inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Delete All Selected</button>
            </div>
            <div class="flex">
                <button  type="button" class="btn btn-warning px-6 py-3 bg-gray-800 text-left text-xs font-medium text-white uppercase tracking-wider border border-transparent rounded-md " id='rule_check_all'  data-checked='false'>check/uncheck all</button>
            </div>

        <div class="mt-5 md:mt-0">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="space-y-6">
                    @if ($this->rules->isNotEmpty())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class=" bg-gray-50">
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Name') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Lists') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Server') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Emails') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Schedule') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Time') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions left') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('ETA') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 bg-gray-50">
                                        <span class="sr-only">{{ __('Actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($this->rules as $rule)
                                    <tr>
                                        <td class="px-4 py-4"><input type="checkbox" class=" sub_chk_rule" data-id="{{ $rule->id }}" data-value="{{ $rule->name }}"></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('rules.show', ['rule' => $rule->id]) }}">{{ $rule->name }}</a>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $rule->listings->pluck('name')->implode(', ') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $rule->connection->name }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $rule->emails_count }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <div>{{ \Illuminate\Support\Str::humanize($rule->schedule) }}</div>

                                            <div>
                                                @if($rule->schedule === 'daily')
                                                    @foreach($rule->schedule_days as $day)
                                                        {{ \Illuminate\Support\Carbon::create($day)->shortDayName }}
                                                    @endforeach
                                                @elseif($rule->schedule === 'weekly')
                                                    {{ \Illuminate\Support\Carbon::create($rule->schedule_weekday)->shortDayName }}
                                                @elseif($rule->schedule === 'monthly')
                                                    @if($rule->schedule_monthday == '-1')
                                                        Last day
                                                    @else
                                                        {{ str_ordinal($rule->schedule_monthday) }}
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <div>{{ \Illuminate\Support\Str::humanize($rule->schedule_time) }}</div>
                                            <div>
                                                @if($rule->schedule_time === 'exact_time')
                                                    {{ \Illuminate\Support\Str::padLeft($rule->schedule_hour, 2, 0) }}:00
                                                @elseif(in_array($rule->schedule_time, ['between', 'spread']))
                                                    {{ \Illuminate\Support\Str::padLeft($rule->schedule_hour_from, 2, 0) }}:00-{{ \Illuminate\Support\Str::padLeft($rule->schedule_hour_to, 2, 0) }}:00
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $rule->actions_left }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            ~{{ $rule->estimated_date->format('d.m.Y') }}
                                        </td>

                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($rule->status === \App\Models\Rule::STATUS_STOPPED)
                                                    <button class="cursor-pointer ml-6 text-sm text-blue-400 focus:outline-none" wire:click="startRule({{ $rule->id }})">
                                                        Start
                                                    </button>
                                                @else
                                                    <button class="cursor-pointer ml-6 text-sm text-yellow-400 focus:outline-none" wire:click="stopRule({{ $rule->id }})">
                                                        Stop
                                                    </button>
                                                @endif

                                                <button class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none" wire:click="cloneRule({{ $rule->id }})">
                                                    Clone
                                                </button>

                                                <a href="{{ route('rules.show', ['rule' => $rule->id]) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                                    {{ __('Details') }}
                                                </a>

                                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmRuleDeletion({{ $rule->id }})">
                                                    {{ __('Delete') }}
                                                </button>


                                                <button class="cursor-pointer ml-6 text-sm text-blue-400 focus:outline-none" wire:click="mauticEmailCron({{ $rule->id }})">{{ __('Test Rule') }}
                                                </button>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            @if (session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                        @if($this->rules->hasPages())
                            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                                {{ $this->rules->links() }}
                            </div>
                        @endif
                    @else
                        <div class="px-4 py-3 sm:px-6">
                            {{ __('No rules yet.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingRuleDeletion">
        <x-slot name="title">
            {{ __('Delete Rule') }}
        </x-slot>

        <x-slot name="content">
            @if($this->ruleBeingDeleted)
                {{ __('Are you sure you want to delete rule :name?', ['name' => $this->ruleBeingDeleted->name]) }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingRuleDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteRule" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@livewireScripts
  
<script>
window.addEventListener('alert', event => { 
             toastr[event.detail.type](event.detail.message, 
             event.detail.title ?? ''), toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                }
            });
</script>


<script type="text/javascript">
    $(document).ready(function(){
        $('#rule_check_all').on('click',function () {
            var d = $(this).data(); // access the data object of the button
            $(':checkbox').prop('checked', !d.checked); // set all checkboxes 'checked' property using '.prop()'
            d.checked = !d.checked;
        });
        $('.delete_all_rule').on('click',function (e) {
            var allVals = [];
            var allValsName = [];
            $('.sub_chk_rule:checked').each(function () {
                allVals.push($(this).attr('data-id'));
                allValsName.push($(this).attr('data-value'))
            });
            if (allVals.length <= 0) {
                alert("Please select row.");
            }else {

                //var join_selected_values = allVals.join("");
                var join_selected_values_name = '<ul class="myList"><li class="ui-menu-item" role="menuitem">' + allValsName.join('</li><li>') + '</li></ul>';
                swal({
                      title: `Are you sure you want to delete the below rule?`,
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
                            url: "/deleteCheckedRule",
                            type:"get",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data:'ids='+allVals,
                            success:function (data) {
                                //console.log(data);
                                window.location.reload();
                               
                            },
                            error: function (data) {
                                alert(data.responseText);
                            }
                        });
                    }
                });
            }
        });
       
    });
</script>
