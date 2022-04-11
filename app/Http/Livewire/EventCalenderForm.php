<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventTemplate;
use App\Models\Groups;
use App\Models\Email;
use App\Models\InvalidGmailId;
use App\Models\ValidGmailId;
use App\Models\GmailConnection;
use App\Models\EventListing;
use App\Models\GmailConnectionGroup;
use App\Tools;
use DB;
use Livewire\Component;
use Carbon\CarbonPeriod;
use Livewire\WithPagination;

use Illuminate\Validation\Rule as ValidationRule;

class EventCalenderForm extends Component
{
    public Event $event;

    public array $list_ids = [];
    public array $connection_ids = [];
    public array $webhook_ids = [];

    public function rules(): array
    {
        return [
            'event.name' => ['required','string','unique:event,name'],
            'event.schedule' => ['nullable', ValidationRule::in(Event::schedules())],
            'event.connection_id' => ['nullable', 'exists:gmail_connections,id'],
            'event.connection_type'  => [ValidationRule::requiredIf(fn () => $this->event->connection_type === 1)],
            'event.template_id' => ['required', 'exists:event_template,id'],
            'event.emails_count' => ['required', 'numeric', 'min:1'],
            'event.schedule_days' => ['array', ValidationRule::requiredIf(fn () => $this->event->schedule === 'daily')],
            'event.schedule_days.*' => ['integer', 'min:1', 'max:7'],
            'event.schedule_monthday' => ['integer', ValidationRule::requiredIf(fn () => $this->event->schedule === 'monthly')],
            'event.schedule_weekday' => ['integer', ValidationRule::requiredIf(fn () => $this->event->schedule === 'weekly')],
            'event.schedule_hour_to' => ['integer', 'min:0', 'max:23', ValidationRule::requiredIf(fn () => in_array($this->event->schedule_time, ['between', 'spread']))],
            'event.schedule_hour_from' => ['integer', 'min:0', 'max:23', ValidationRule::requiredIf(fn () => in_array($this->event->schedule_time, ['between', 'spread']))],
            'event.timezone' => ['nullable', ValidationRule::in($this->timezones)],
            'event.schedule_time' => [
                'nullable', ValidationRule::in(Event::scheduleTimes())
            ],
            'list_ids' => ['array','min:1'],
            'connection_ids' => ['array','min:1'],
        ];
    }
    public function render()
    {
        return view('livewire.event-calender-form');
    }

    public function mount(Event $event): void
    {
        $this->event = $event;  
        $this->list_ids = $event->listings()
            ->pluck('id')
            ->map(fn ($id) => (string)$id)
            ->toArray();

        $this->connection_ids = $event->groups()
            ->pluck('id')
            ->map(fn ($id) => (string)$id)
            ->toArray();

        $this->event->connection_type = $this->event->connection_type ?? 0;
        $this->event->schedule = $this->event->schedule ?? 'daily';
        $this->event->schedule_time = $this->event->schedule_time ?? 'random';
        $this->event->timezone = $this->event->timezone ?? now()->tzName;
    }

    public function getListingsProperty()
    {
        return EventListing::all();
    }

    public function getConnectionsProperty()
    {
        return GmailConnection::all();
    }

    public function getTemplatesProperty()
    {
        return EventTemplate::all();
    }


    public function getTimezonesProperty(): array
    {
        return timezone_identifiers_list();
    }

    public function getEventGroupsProperty(){
        return Groups::get();
    }

    public function getGmailConnectionsProperty(){
        return GmailConnection::get();
    }

    public function getWeekPeriodProperty()
    {
        return new CarbonPeriod(
            Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
        );
    }

    public function getHoursProperty(): array
    {
        return range(0, 23);
    }

    public function submit():void
    {
        if($this->event->id == ''){
            $this->validate();
        }
        $this->event->save();

        $connectionLists = DB::table('gmail_connection_groups')->whereIn('groups_id',$this->connection_ids)->get()->toArray();
        foreach($connectionLists as $value){
            GmailConnectionGroup::where('groups_id',$value->groups_id)->update(
                                        [ 
                                            'event_id' => $this->event->id,
                                            'sync_status'        =>'no'
                                        ]);
        }
        
        $this->event->listings()->sync($this->list_ids); 

        $this->event->groups()->sync($this->connection_ids); 

        if ($this->event->wasRecentlyCreated) 
        {
            $this->redirectRoute('eventcalender.index');
        }else 
        {
            $this->redirectRoute('eventcalender.index');
        }
        $this->emit('saved');

    }
}