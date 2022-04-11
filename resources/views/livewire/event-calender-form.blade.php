<div>
    <x-form-section submit="submit">
        <x-slot name="title">
            Event Data
        </x-slot>

        <x-slot name="description">
            Events Detailed Information
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6">
                <x-jet-label for="event_name" value="{{ __('Event Name') }}" />
                <x-jet-input id="event_name" type="text" class="mt-1 block w-full" wire:model.defer="event.name" />
                <x-jet-input-error for="event.name" class="mt-2" />
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
                <x-jet-label for="connection_type" value="{{ __('Connection Type') }}" />
                <x-select name="connection_type" class="mt-1" wire:model="event.connection_type">
                    <option value="0">Group</option>
                    <option value="1">Single</option>
                </x-select>
                <x-jet-input-error for="connection_type" class="mt-2" />
                @if($this->event->connection_type == 0)

                <div class="flex flex-col">
                    <x-jet-label for="connection_ids" value="Groups" />
                    @foreach($this->eventgroups as $eventgroup)
                        <label class="inline-flex items-center mt-3">
                            <input name="connection_ids" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="connection_ids" value="{{ $eventgroup->id }}">
                            <span class="ml-2 text-gray-700">{{ $eventgroup->name }}</span>
                        </label>
                    @endforeach
                </div>
                <x-jet-input-error for="list_ids" class="mt-2" />
                @endif
                @if($this->event->connection_type == 1)
                    <x-jet-label for="connection_id" value="{{ __('Accounts') }}" />
                    <x-select name="connection_id" class="mt-1" wire:model="event.connection_id" id="mauticstage_serach">
                        <option value=""></option>
                        @foreach($this->connections as $connection)
                        <option value="{{ $connection->id }}">
                            {{ $connection->email_id }}
                        </option>
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="event.connection_id" class="mt-2" />
                @endif
            </div>
            <div class="col-span-3">
                <x-jet-label for="template_id" value="{{ __('Template') }}" />
                <x-select name="template_id" class="mt-1" wire:model="event.template_id">
                    <option value=""></option>
                    @foreach($this->templates as $template)
                    <option value="{{ $template->id }}">
                        {{ $template->name }}
                    </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="event.template_id" class="mt-2" />
            </div>

            <div class="col-span-3">
                <x-jet-label for="emails_count" value="{{ __('Emails per day') }}" />
                <x-jet-input id="emails_count" type="number" class="mt-1 block w-full" min="1" wire:model.defer="event.emails_count" />
                <x-jet-input-error for="event.emails_count" class="mt-2" />
            </div>
            <div class="col-span-3">
                <x-jet-label for="schedule" value="{{ __('Schedule') }}" />
                <x-select name="schedule" class="mt-1" wire:model="event.schedule">
                    @foreach(\App\Models\Event::schedules() as $schedule)
                    <option value="{{ $schedule }}">
                        {{ \Illuminate\Support\Str::humanize($schedule) }}
                    </option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="event.schedule" class="mt-2" />
            </div>
            <div class="col-span-3">
                @if($event->schedule === 'daily')
                <div class="flex flex-col">
                    <x-jet-label for="schedule_days" value="Every" />
                    @foreach($this->weekPeriod as $day)
                        <label class="inline-flex items-center mt-3">
                            <input name="schedule_days" type="checkbox" class="form-checkbox h-5 w-5 text-gray-600" wire:model="event.schedule_days" value="{{ $day->format('N') }}">
                            <span class="ml-2 text-gray-700">{{ $day->format('l') }}</span>
                        </label>
                    @endforeach
                    <x-jet-input-error for="event.schedule_days" class="mt-2" />
                </div>
                @elseif($event->schedule === 'weekly')
                    <x-jet-label for="schedule_weekday" value="Every" />
                    <x-select name="schedule_weekday" class="mt-1" wire:model.defer="event.schedule_weekday">
                        <option value=""></option>
                        @foreach($this->weekPeriod as $day)
                            <option value="{{ $day->format('N') }}">
                                {{ $day->format('l') }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="event.schedule_weekday" class="mt-2" />
                @elseif($event->schedule === 'monthly')
                    <x-jet-label for="schedule_monthday" value="Every" />
                    <x-select name="schedule_monthday" class="mt-1" wire:model.defer="event.schedule_monthday">
                        <option value=""></option>
                        @foreach(range(1, 31) as $day)
                            <option value="{{ $day }}">{{ str_ordinal($day) }}</option>
                        @endforeach
                        <option value="-1">Last day</option>
                    </x-select>
                    <x-jet-input-error for="event.schedule_monthday" class="mt-2" />
                @endif
            </div>
            <div class="col-span-2">
                <x-jet-label value="{{ __('Schedule time') }}" />

                <div class="flex flex-col">
                    @foreach(\App\Models\Event::scheduleTimes() as $option)
                    <label class="inline-flex items-center mt-3">
                        <input type="radio" class="form-radio h-5 w-5 text-gray-600" wire:model="event.schedule_time" value="{{ $option }}">
                        <span class="ml-2 text-gray-700">{{ \Illuminate\Support\Str::humanize($option) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="col-span-4">
                <x-jet-label value="{{ __('Timezone') }}" />
                <x-select name="timezone" class="mt-1" wire:model.defer="event.timezone">
                    @foreach($this->timezones as $timezone)
                        <option value="{{ $timezone }}">{{ $timezone }}</option>
                    @endforeach
                </x-select>
                <x-jet-input-error for="event.timezone" class="mt-2" />

                @if($event->schedule_time === 'exact_time')
                    <x-select name="schedule_hour" class="mt-1" wire:model.defer="event.schedule_hour">
                        @foreach($this->hours as $hour)
                            <option value="{{ $hour }}">{{ \Illuminate\Support\Str::padLeft($hour, 2, 0) }}:00</option>
                        @endforeach
                    </x-select>
                    <x-jet-input-error for="event.schedule_hour" class="mt-2" />
                @elseif(in_array($event->schedule_time, ['between', 'spread']))
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <x-select name="schedule_hour_from" class="mt-1" wire:model.defer="event.schedule_hour_from">
                                @foreach($this->hours as $hour)
                                    <option value="{{ $hour }}">{{ \Illuminate\Support\Str::padLeft($hour, 2, 0) }}:00</option>
                                @endforeach
                            </x-select>
                            <x-jet-input-error for="event.schedule_hour_from" class="mt-2" />
                        </div>
                        <div class="col-span-1">
                            <x-select name="schedule_hour_to" class="mt-1" wire:model.defer="event.schedule_hour_to">
                                @foreach($this->hours as $hour)
                                    <option value="{{ $hour }}">{{ \Illuminate\Support\Str::padLeft($hour, 2, 0) }}:00</option>
                                @endforeach
                            </x-select>
                            <x-jet-input-error for="event.schedule_hour_to" class="mt-2" />
                        </div>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-jet-button>
                
                {{ __(!$this->event->exists ? 'Create' : 'Update') }}
            </x-jet-button>
        </x-slot>
    </x-form-section>
</div>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#start_time').on('change',function(){
             var value_to_Add = moment.utc($(this).val(),'hh:mm A').add(30,'minutes').format('hh:mm A');
             $('#end_time').val(value_to_Add);
        });
    });
</script>




