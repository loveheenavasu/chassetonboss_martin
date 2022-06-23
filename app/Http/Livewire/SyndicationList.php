<?php

namespace App\Http\Livewire;
use Illuminate\Http\Request;
use App\Models\Syndication;
use App\Models\SyndicationConnection;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Helpers\FlashNotificationHelper;

class SyndicationList extends Component
{   
    use WithPagination;
    public bool $confirmingSyndicationDeletion = false;
    public ?Syndication $syndicationBeingDeleted = null;
    public bool $queryParamsValues = false;
    public string $queryparams = '';
    public string $final_query_urls = '';
    public ?Syndication $idSyndication = null;
    public $flag =0;

    public function rules(): array
    {
        return [
            'queryparams' => ['required']
        ];
    }

    public function getSyndicationsProperty()
    {
        return Syndication::latest()->paginate(10);
    }

    public function confirmSyndicationDeletion(Syndication $syndication): void
    {
        $this->confirmingSyndicationDeletion = true;
        $this->syndicationBeingDeleted = $syndication;
    }

    public function deleteSyndication(): void
    {
        if (! is_null($this->syndicationBeingDeleted)) {
            $this->syndicationBeingDeleted->delete();
        }

        $this->confirmingSyndicationDeletion = false;
    }


    public function QueryParams(Syndication $syndication): void
    {  
        $this->queryParamsValues = true;
        $this->idSyndication = $syndication;
    }

    public function addQueryParamsValue(Syndication $syndication,Request $request)
    {
        $this->validate();
        $params_vals = $request['serverMemo']['data']['queryparams'];
        $syndication =$this->idSyndication;
        $syndication_urls= $syndication->copiedValue();
        $syndicateUrls = explode('https://',$syndication_urls);
        foreach($syndicateUrls as $urls){
            if(!empty($urls)){
                $new_urls[] = 'https://'.trim($urls).$params_vals;
            }
        }
        if(!empty($new_urls)){
             $this->final_query_urls = implode("\n",$new_urls);
        }
        else{
            return session()->flash('message', 'No Urls To link parameters.');
        }
       
        if(!empty($this->final_query_urls)){
            $this->flag = 1;
        }
    }

    public function downloadParamsUrls(Syndication $syndication)
    {
        if($this->flag  == 1){
            $final_syndicate_urls = $this->final_query_urls;
            return response()->streamDownload(function () use ($final_syndicate_urls,$syndication) {
                echo $final_syndicate_urls;
            }, 'Syndication QueryParams Urls ' . $syndication->content->name . '.txt');
        }
    }

    public function hasSyndications(Syndication $syndication): bool
    {
        return $syndication->syndicatedConnections()->count() > 0;
    }

    public function downloadLinksFile(Syndication $syndication)
    {
        if (! $syndication->content) {
            return;
        }

        return response()->streamDownload(function () use ($syndication) {
            echo $syndication->copiedValue();
        }, 'Syndication of ' . $syndication->content->name . '.txt');
    }
}
