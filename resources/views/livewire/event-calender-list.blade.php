<div>
    <div class="md:grid md:gap-6">
        <x-jet-section-title>
            <x-slot name="title">{{ __('Manage Events Rule') }}</x-slot>
            <x-slot name="description">Configure event rules.</x-slot>
        </x-jet-section-title>
        <div class="mt-5 md:mt-0">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="space-y-6">
                    @if ($this->Eventgroups->isNotEmpty())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                                <tr>
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
                                    <th scope="col" class="px-6 py-3 bg-gray-50  text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($this->Eventgroups as $event)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <a href="{{ route('eventcalender.edit', $event->id) }}">{{ $event->name }}</a>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $event->listings->pluck('name')->implode(', ') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        @if(isset($event->connection->email_id))
                                            {{ $event->connection->email_id }}
                                        @else
                                            {{ $event->groups->pluck('name')->implode(', ') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $event->emails_count }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <div>{{ \Illuminate\Support\Str::humanize($event->schedule) }}</div>


                                            <div>
                                                @if($event->schedule === 'daily')
                                                    @foreach($event->schedule_days as $day)
                                                        {{ \Illuminate\Support\Carbon::create($day)->shortDayName }}
                                                    @endforeach
                                                @elseif($event->schedule === 'weekly')
                                                    {{ \Illuminate\Support\Carbon::create($event->schedule_weekday)->shortDayName }}
                                                @elseif($event->schedule === 'monthly')
                                                    @if($event->schedule_monthday == '-1')
                                                        Last day
                                                    @else
                                                        {{ str_ordinal($event->schedule_monthday) }}
                                                    @endif
                                                @endif
                                            </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <div>{{ \Illuminate\Support\Str::humanize($event->schedule_time) }}</div>
                                            <div>
                                                @if($event->schedule_time === 'exact_time')
                                                    {{ \Illuminate\Support\Str::padLeft($rule->schedule_hour, 2, 0) }}:00
                                                @elseif(in_array($event->schedule_time, ['between', 'spread','event_time']))
                                                    {{ \Illuminate\Support\Str::padLeft($event->schedule_hour_from, 2, 0) }}:00-{{ \Illuminate\Support\Str::padLeft($event->schedule_hour_to, 2, 0) }}:00
                                                @endif
                                            </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $event->actions_left }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                           {{ $event->estimated_date->format('d.m.Y') }} 
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($event->status === \App\Models\Event::STATUS_STOPPED)
                                                    <button class="cursor-pointer ml-6 text-sm text-blue-400 focus:outline-none" wire:click="startEvent({{ $event->id }})">
                                                        Start
                                                    </button>
                                                @else
                                                    <button class="cursor-pointer ml-6 text-sm text-yellow-400 focus:outline-none" wire:click="stopEvent({{ $event->id }})">
                                                        Stop
                                                    </button>
                                                @endif

                                                <button class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none" wire:click="cloneEvent({{ $event->id }})">
                                                    Clone
                                                </button>

                                                <a href="{{route('eventcalender.edit', $event->id) }}" class="cursor-pointer ml-6 text-sm text-gray-400 focus:outline-none">
                                                    {{ __('Details') }}
                                                </a>

                                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmEventDeletion({{ $event->id }})">
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>
                                        </td>

                                </tr>
                                @endforeach
                            </tbody>
                    </table>  
                </div>
            </div>
                 @if($this->Eventgroups->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $this->Eventgroups->fragment('')->links() }}
                        </div>
                    @endif
                @else
                    <div>{{ __('No events rule yet.') }}</div>
                @endif
        </div>
    </div>
    <x-jet-confirmation-modal wire:model="confirmingEventDeletion">
        <x-slot name="title">
            {{ __('Delete Rule') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this Event?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingEventDeletion')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteEvent" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>