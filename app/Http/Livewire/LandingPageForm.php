<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\LandingPage;
use App\Models\Connection;
use App\Models\LandingTemplate;
use App\Models\Tokens;
use App\Models\Profiles;
use App\Tools;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LandingPageForm extends Component
{
    public LandingPage $landingpage;
    public array $json_data = [];
    public array $connection_ids = [];
    public $selectAll =false;

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::current())->get();
    }

    public function getTemplatesProperty()
    {
        return LandingTemplate::all();
    }

     public function getTokensProperty()
    {
        return Tokens::all();
    }
     public function getProfilesProperty()
    {
        return Profiles::all();
    }

    public function mount(LandingPage $landingpage): void
    {
        $json_data_array = [];
        $this->landingpage = $landingpage;
        $this->landingpage->tools = Tools::current();
        $this->landingpage->load('landingpageConnections');

        $this->connection_ids = $this->landingpage
            ->landingpageConnections
            ->map(fn ($s) => (string) $s->connection_id)
            ->toArray();
    }

    public function wasConnectionDeployed(Connection $connection): bool
    {
        $sc = $this->landingpage
            ->landingpageConnections()
            ->firstWhere('connection_id', '=', $connection->id);

        if (!$sc) {
            return false;
        }

        return $sc->was_deployed;
    }

    public function rules(): array
    {
        return [
            'landingpage.tools' => ['required', Rule::in(Tools::all())],
            'connection_ids' => ['array', 'min:1'],
            'connection_ids.*' => ['exists:connections,id'],
            'landingpage.profile_id' => ['nullable', 'exists:token_profile,id'],
            'landingpage.landing_template_id' => ['required', 'exists:landing_templates,id'],
            'landingpage.slug' => ['required', 'string'],
        ];
    }

    public function submit(DeployPage $deployer): void
    {   
        if($this->landingpage->id == ''){
            $this->validate();
        }
        $this->landingpage->save();
        $this->landingpage->landingpageConnections()->delete();
        try {

            collect($this->connection_ids)->each(function ($connectionId) {
                $this->landingpage->landingpageConnections()->create([
                    'connection_id' => $connectionId,
                    'was_deployed' => false
                ]);
            });

            $deployer->deploylandingpage($this->landingpage);

            // session()->flash('copyToClipboard', [
            //     'text' => 'Content urls copied to clipboard',
            //     'value' => $this->landingpage->copiedValue()
            // ]);

            $this->redirectRoute('landingpages.index');
        }catch(\Throwable $e){
            $connectionName = Connection::whereIn('id',$this->connection_ids)->pluck('name')->toArray();
            session()->flash('message', 'Domain not connected '.implode(',', $connectionName).'');
        }
    }

    public function getCurrentToollProperty(){

       return Tools::current();
    }

    public function updatedSelectAll($value){

        if($value){
            $this->connection_ids = Connection::byTool(Tools::LANDING_PAGE)->pluck('id')->toArray();
        }else{
            $this->connection_ids = [];
        }
        
    }


}
