<div>
    <x-jet-form-section submit="save">
        <x-slot name="title">
            Rule data
        </x-slot>

        <x-slot name="description">
            Automatic tasks configuration.
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="rule.name" />
                <x-jet-input-error for="rule.name" class="mt-2" />
            </div>

            <div class="col-span-3">
                <div class="flex flex-col">
                    <x-jet-label for="list_ids" value="Lists" />
                    @foreach($this->listings as $listing)
                        <label class="inline-flex items-center mt-3">
                            <input name="list_ids" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="list_ids" value="{{ $listing->id }}">
                            <span class="ml-2 text-gray-700">{{ $listing->name }}</span>
                        </label>
                    @endforeach
                </div>
                <x-jet-input-error for="list_ids" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="connection_id" value="{{ __('Server') }}" />
                <x-select name="connection_id" class="mt-1" wire:model="rule.connection_id" id="mauticstage_serach">
                    <option value=""></option>
                    @foreach($this->connections as $connection)
                    <option value="{{ $connection->id }}">
                        {{ $connection->name }}
                    </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="rule.connection_id" class="mt-2" />

                @if($rule->requiresStage())
                    <div class="mautlist">
                        <x-jet-label for="stage_id" value="{{ __('List') }}" class="mt-2" />
                        <x-select name="stage_id" class="mt-1" wire:model.defer="rule.stage_id" id="mauticstage">
                            <option value=""></option>
                            @foreach($this->mauticStages as $stageName)
                            <option value="{{ $stageName->id }}">
                                {{ $stageName->name }}
                            </option>
                            @endforeach
                        </x-select>
                        
                        <i class="fa fa-refresh refreshStage" id="refreshStage" aria-hidden="true"></i>
                        <x-jet-input-error for="rule.stage_id" class="mt-2" />
                    </div>
                @endif
                @if($rule->requiresWebhook())
                    <div class="webhookcheck">
                        <input name="rule.webhook_split" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="webhook_split" wire:model="rule.webhook_split">
                        <span class="ml-2 text-gray-700">Split</span>
                        <x-jet-input-error for="rule.webhook_split" class="mt-2" />
                    </div>
                    @if($rule->webhook_split == true)
                    <div class="webhookList">
                        <x-jet-label for="webhook_ids" value="List of Webhooks" />
                        @foreach($this->webhookList as $webhookName)
                            <label class="inline-flex items-center mt-3">
                                <input name="webhook_ids" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="webhook_ids" value="{{ $webhookName->id }}">
                                <span class="ml-2 text-gray-700">{{ $webhookName->name }}</span>
                            </label>
                        @endforeach
                        <x-jet-input-error for="rule.webhook_ids" class="mt-2" />
                    </div>  
                    @endif
                    @if(!empty($webhook_ids))
                        <div class="randseqcheckbox">
                            <x-jet-label for="webhook_ids" value="Select Email Type" />

                            <label class="inline-flex items-center mt-3">
                                <input type="radio" class="form-radio h-5 w-5 text-gray-600" wire:model="rule.emailtype" value="sequence">
                                <span class="ml-2 text-gray-700">Sequence</span>
                            </label>

                            <label class="inline-flex items-center mt-3">
                                <input type="radio" class="form-radio h-5 w-5 text-gray-600" wire:model="rule.emailtype" value="random">
                                <span class="ml-2 text-gray-700">Random</span>
                            </label>
                            <x-jet-input-error for="rule.emailtype" class="mt-2" />
                        </div>
                    @endif
                @endif
            </div>

            <div class="col-span-6">
                <x-jet-label for="emails_count" value="{{ __('Emails per day') }}" />
                <x-jet-input id="emails_count" type="number" class="mt-1 block w-full" min="1" wire:model.defer="rule.emails_count" />
                <x-jet-input-error for="rule.emails_count" class="mt-2" />
            </div>

            <!-- <div class="col-span-6">
                <label class="inline-flex items-center mt-3">
                    <input name="randomize_emails_order" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="rule.randomize_emails_order" value="1">
                    <span class="ml-2 text-gray-700">{{ __('Randomize Emails Order') }}</span>
                </label>
                <x-jet-input-error for="rule.randomize_emails_order" class="mt-2" />
            </div> -->

            <div class="col-span-3">
                <x-jet-label for="schedule" value="{{ __('Schedule') }}" />
                <x-select name="schedule" class="mt-1" wire:model="rule.schedule">
                    @foreach(\App\Models\Rule::schedules() as $schedule)
                    <option value="{{ $schedule }}">
                        {{ \Illuminate\Support\Str::humanize($schedule) }}
                    </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="rule.schedule" class="mt-2" />
            </div>

            <div class="col-span-3">
                @if($rule->schedule === 'daily')
                <div class="flex flex-col">
                    <x-jet-label for="schedule_days" value="Every" />
                    @foreach($this->weekPeriod as $day)
                        <label class="inline-flex items-center mt-3">
                            <input name="schedule_days" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="rule.schedule_days" value="{{ $day->format('N') }}">
                            <span class="ml-2 text-gray-700">{{ $day->format('l') }}</span>
                        </label>
                    @endforeach
                    <x-jet-input-error for="rule.schedule_days" class="mt-2" />
                </div>
                @elseif($rule->schedule === 'weekly')
                    <x-jet-label for="schedule_weekday" value="Every" />
                    <x-select name="schedule_weekday" class="mt-1" wire:model.defer="rule.schedule_weekday">
                        <option value=""></option>
                        @foreach($this->weekPeriod as $day)
                            <option value="{{ $day->format('N') }}">
                                {{ $day->format('l') }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="rule.schedule_weekday" class="mt-2" />
                @elseif($rule->schedule === 'monthly')
                    <x-jet-label for="schedule_monthday" value="Every" />
                    <x-select name="schedule_monthday" class="mt-1" wire:model.defer="rule.schedule_monthday">
                        <option value=""></option>
                        @foreach(range(1, 31) as $day)
                            <option value="{{ $day }}">{{ str_ordinal($day) }}</option>
                        @endforeach
                        <option value="-1">Last day</option>
                    </x-select>
                    <x-jet-input-error for="rule.schedule_monthday" class="mt-2" />
                @endif
            </div>

            <div class="col-span-2">
                <x-jet-label value="{{ __('Schedule time') }}" />

                <div class="flex flex-col">
                    @foreach(\App\Models\Rule::scheduleTimes() as $option)
                    <label class="inline-flex items-center mt-3">
                        <input type="radio" class="form-radio h-5 w-5 text-gray-600" wire:model="rule.schedule_time" value="{{ $option }}">
                        <span class="ml-2 text-gray-700">{{ \Illuminate\Support\Str::humanize($option) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="col-span-4">
                <x-jet-label value="{{ __('Timezone') }}" />
                <x-select name="timezone" class="mt-1" wire:model.defer="rule.timezone">
                    @foreach($this->timezones as $timezone)
                        <option value="{{ $timezone }}">{{ $timezone }}</option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="rule.timezone" class="mt-2" />

                @if($rule->schedule_time === 'exact_time')
                    <x-select name="schedule_hour" class="mt-1" wire:model.defer="rule.schedule_hour">
                        @foreach($this->hours as $hour)
                            <option value="{{ $hour }}">{{ \Illuminate\Support\Str::padLeft($hour, 2, 0) }}:00</option>
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="rule.schedule_hour" class="mt-2" />
                @elseif(in_array($rule->schedule_time, ['between', 'spread']))
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <x-select name="schedule_hour_from" class="mt-1" wire:model.defer="rule.schedule_hour_from">
                                @foreach($this->hours as $hour)
                                    <option value="{{ $hour }}">{{ \Illuminate\Support\Str::padLeft($hour, 2, 0) }}:00</option>
                                @endforeach
                            </x-select>
                            <x-jet-input-error for="rule.schedule_hour_from" class="mt-2" />
                        </div>
                        <div class="col-span-1">
                            <x-select name="schedule_hour_to" class="mt-1" wire:model.defer="rule.schedule_hour_to">
                                @foreach($this->hours as $hour)
                                    <option value="{{ $hour }}">{{ \Illuminate\Support\Str::padLeft($hour, 2, 0) }}:00</option>
                                @endforeach
                            </x-select>
                            <x-jet-input-error for="rule.schedule_hour_to" class="mt-2" />
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-span-6">
                <x-jet-label for="notes" value="{{ __('Notes') }}" />
                <textarea class="form-input rounded-md shadow-sm mt-1 block w-full" rows="7" wire:model.defer="rule.notes"></textarea>
                <x-jet-input-error for="rule.notes" class="mt-2" />
            </div>
            <div class="col-span-6">
                <div class="webhookcheck">
                    <input name="rule.webhook_send" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" id="webhook_send" wire:model="rule.webhook_send">
                    <span class="ml-2 text-gray-700">Send Info to Webhook</span>
                    <x-jet-input-error for="rule.webhook_send" class="mt-2" />
                </div>
                @if($rule->webhook_send == true)
                    <x-select name="webhook_id_selected" class="mt-1" wire:model="rule.webhook_id_selected">
                        <option value=""></option>
                        @foreach($this->connections as $connection)
                            @if($connection->id !=1){
                            <option value="{{ $connection->id }}">
                                {{ $connection->name }}
                            </option>
                            @endif
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="rule.webhook_id_selected" class="mt-2" />
                @endif
             
            </div>

{{--            <div class="col-span-6">--}}
{{--                <div class="grid grid-cols-6 gap-6">--}}
{{--                    <div class="col-span-2">--}}
{{--                        <x-select name="operation" class="mt-1">--}}
{{--                            <option value="increase">Increase</option>--}}
{{--                            <option value="decrease">Decrease</option>--}}
{{--                        </x-select>--}}
{{--                    </div>--}}
{{--                    <div class="col-span-1 text-center pt-1">until reach</div>--}}
{{--                    <div class="col-span-2">--}}
{{--                        <x-jet-input type="number" class="mt-1 block w-full" min="0" />--}}
{{--                    </div>--}}
{{--                    <div class="col-span-1">--}}
{{--                        <x-select name="operation" class="mt-1">--}}
{{--                            <option value="count">Count</option>--}}
{{--                            <option value="percent">%</option>--}}
{{--                        </x-select>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}

{{--            <div class="col-span-6">--}}
{{--                <x-jet-label for="emails_count" value="{{ __('Spread on') }}" />--}}
{{--                <x-jet-input id="emails_count" type="text" class="mt-1 block w-full" wire:model.defer="rule.emails_count" />--}}
{{--                <x-jet-input-error for="rule.emails_count" class="mt-2" />--}}
{{--            </div>--}}
        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __(!$rule->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>
</div>
<div class="loading" style="display: none;">Loading&#8230;</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<style type="text/css">
body .shadow {
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 10%) !important;
    overflow: visible;
}
a:hover{
    text-decoration: none !important;
}
/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}
.mautlist {
    position: relative;
}
.mautlist #mauticstage {
    width: 90%;
}
.mautlist .text-gray-700 {
    right: 38px;
}
.mautlist .refreshStage {
    position: absolute;
    bottom: 16px;
    right: 0;
    cursor: pointer;
}
/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}
.webhookList{
    height: 150px;
    width: 350px;
    overflow-y: auto;
    float: left;
    position: relative;
    margin: 10px 0px 0px 0px;
}
/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@media(max-width: 500px){
    .mautlist .text-gray-700 {
    right: 16px;
    }
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '#refreshStage',function(){
            var mauticvalue = $('#mauticstage_serach').find(":selected").val();
            var mauticvalue_selected = $('#mauticstage').find(":selected").val();
            $('#mauticstage').html('');
            $('.loading').show();
            $.ajax(
            {
                url: "/refresh-mautic",
                data: {data:mauticvalue,slected:mauticvalue_selected}, 
                success: function(result){
                    $('.loading').hide();
                    $('#mauticstage').append(result);
                    $("#mauticstage > option").each(function() {
                        if(this.value == mauticvalue_selected){
                            $(this).attr('selected','selected')
                        }
                    });
                    
                }
            });
        });
        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                $('#custom-webhooks').show()
            }
            else if($(this).prop("checked") == false){
                $('#custom-webhooks').hide()
            }
        });
    });
</script>