<?php

namespace App\Http\Livewire;

use App\Models\Connection;
use App\Models\Listing;
use App\Models\Rule;
use App\Tools;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

use Illuminate\Validation\Rule as ValidationRule;

class RuleForm extends Component
{
    public Rule $rule;

    public array $list_ids = [];

    public array $webhook_ids = [];

    public function rules(): array
    {
        return [
            'rule.name' => ['required', 'string','unique:rules,name'],
            'rule.connection_id' => ['required', 'exists:connections,id'],
            'rule.stage_id' => [
                ValidationRule::requiredIf(fn () => $this->rule->requiresStage())
            ],
            // 'rule.stage_id'  => ['required_if:rule.connection_id,Chassetonboss'],
            'rule.emails_count' => ['required', 'numeric', 'min:1'],
            'rule.randomize_emails_order' => ['boolean'],
            'rule.timezone' => ['required', ValidationRule::in($this->timezones)],
            'rule.schedule' => ['required', ValidationRule::in(Rule::schedules())],
            'rule.schedule_days' => ['array', ValidationRule::requiredIf(fn () => $this->rule->schedule === 'daily')],
            'rule.schedule_days.*' => ['integer', 'min:1', 'max:7'],
            'rule.schedule_weekday' => ['integer', ValidationRule::requiredIf(fn () => $this->rule->schedule === 'weekly')],
            'rule.schedule_monthday' => ['integer', ValidationRule::requiredIf(fn () => $this->rule->schedule === 'monthly')],
            'rule.schedule_time' => [
                'required', ValidationRule::in(Rule::scheduleTimes())
            ],
            'rule.schedule_hour' => ['integer', 'min:0', 'max:23', ValidationRule::requiredIf(fn () => $this->rule->schedule_time === 'exact')],
            'rule.schedule_hour_from' => ['integer', 'min:0', 'max:23', ValidationRule::requiredIf(fn () => in_array($this->rule->schedule_time, ['between', 'spread']))],
            'rule.schedule_hour_to' => ['integer', 'min:0', 'max:23', ValidationRule::requiredIf(fn () => in_array($this->rule->schedule_time, ['between', 'spread']))],
            'rule.notes' => ['nullable', 'string'],
            'rule.webhook_split' => ['nullable'],
            'webhook_ids' => ['array'],
            'rule.emailtype' => [ValidationRule::requiredIf(fn () => $this->rule->webhook_split == true)],
            'list_ids' => ['array', 'min:1'],
            'rule.webhook_send' => ['nullable'],
            'list_ids.*' => ['required', 'exists:listings,id'],
            'rule.webhook_id_selected' => ['nullable', 'string',ValidationRule::requiredIf(fn () => $this->rule->webhook_send == true)]

        ];
    }

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::current())->get();
    }

    public function getListingsProperty()
    {
        return Listing::all();
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

    public function getTimezonesProperty(): array
    {
        return timezone_identifiers_list();
    }

    public function getStagesProperty()
    {
        return [
            1 => 'Discovery',
            2 => 'Engaged',
            3 => 'Proposal',
            4 => 'Bought'
        ];
    }

    public function getWebhooksProperty()
    {
        return [
            1 => 'webhook',
        ];
    }

    public function updatedRuleConnectionId(string $connectionId): void
    {
        if ($connection = Connection::query()->find($connectionId)) {
            $this->rule->connection()->associate($connection);
        }
    }

    public function mount(Rule $rule): void
    {
       
        $this->rule = $rule;

        $this->list_ids = $rule->listings()
            ->pluck('id')
            ->map(fn ($id) => (string)$id)
            ->toArray();

        $this->webhook_ids = $rule->webhooks()
            ->pluck('id')
            ->map(fn ($id) => (string)$id)
            ->toArray();

        $this->rule->schedule = $this->rule->schedule ?? 'daily';
        $this->rule->schedule_time = $this->rule->schedule_time ?? 'random';
        $this->rule->timezone = $this->rule->timezone ?? now()->tzName;
        $this->rule->randomize_emails_order = $this->rule->randomize_emails_order ?? false;
    }

    public function save(): void
    {
        if($this->rule->id == ''){
            $this->validate();
        }
        $this->rule->save();

        $this->rule->listings()->sync($this->list_ids); 

        $this->rule->webhooks()->sync($this->webhook_ids); 

        if ($this->rule->wasRecentlyCreated) {
            $this->redirectRoute('rules.index');
        } else {
            $this->redirectRoute('rules.index');
        }

        $this->emit('saved');
    }

    public function getmauticStagesProperty(){
        $apiData = Connection::where('id',$this->rule->connection_id)->first()->toArray();
        $login = $apiData['username'];
        $password = $apiData['password'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiData['base_url'].'/api/stages',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
            'token: a424d49046a2cc1acf014ebaff5987964332ef85',
            'Authorization: Basic '.base64_encode($login . ":" . $password)
          ),
        ));

        $response = curl_exec($curl);
         curl_close($curl);
        $allstages = json_decode($response);
        return $allstages->stages;
        
    }
    public function getwebhookListProperty(){
        $allWebhooks = Connection::where('type','webhook')->get();
        return $allWebhooks;
    }

}
