<?php

namespace App\Http\Livewire;

use App\Tools;
use App\Actions\DeploySyndication;
use App\Models\Connection;
use App\Models\Content;
use App\Models\Syndication;
use Livewire\Component;

class SyndicationForm extends Component
{
    public Syndication $syndication;

    public array $connection_ids = [];
    public  $syndication_msg = false;

    public $selectAll =false;

    public function mount(Syndication $syndication): void
    {
        $this->syndication = $syndication;

        $this->syndication->load('syndicatedConnections');

        $this->connection_ids = $this->syndication
            ->syndicatedConnections
            ->map(fn ($s) => (string) $s->connection_id)
            ->toArray();
    }

    public function getContentsProperty()
    {
        return Content::query()->get();
    }

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::SYNDICATION)->get();
    }

    public function wasConnectionDeployed(Connection $connection): bool
    {
        $sc = $this->syndication
            ->syndicatedConnections()
            ->firstWhere('connection_id', '=', $connection->id);

        if (!$sc) {
            return false;
        }

        return $sc->was_deployed;
    }

    public function rules(): array
    {
        return [
            'syndication.content_id' => ['required', 'exists:contents,id'],
            'syndication.slug' => ['required', 'string'],
            'connection_ids' => ['array', 'min:1'],
            'connection_ids.*' => ['exists:connections,id']
        ];
    }

    public function submit(DeploySyndication $deployer): void
    {
        $this->validate();

        $this->syndication->save();

        $this->syndication->syndicatedConnections()->delete();
        try {

            collect($this->connection_ids)->each(function ($connectionId) {
                $this->syndication->syndicatedConnections()->create([
                    'connection_id' => $connectionId,
                    'was_deployed' => false
                ]);
            });

            $deployer->deploy($this->syndication);

            session()->flash('copyToClipboard', [
                'text' => 'Content urls copied to clipboard',
                'value' => $this->syndication->copiedValue()
            ]);

            $this->redirectRoute('syndications.index');
        }catch(\Throwable $e){
            $connectionName = Connection::whereIn('id',$this->connection_ids)->pluck('name')->toArray();
            session()->flash('message', 'Domain not connected '.implode(',', $connectionName).'');
        }
    }

    public function updatedSelectAll($value){

        if($value){
            $this->connection_ids = Connection::byTool(Tools::SYNDICATION)->pluck('id')->toArray();
        }else{
            $this->connection_ids = [];
        }
        
    }
}
