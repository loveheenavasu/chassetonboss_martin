<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\LandingPage;
use App\Models\LandingPageConnection;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\Helpers\FlashNotificationHelper;


class LandingPageList extends Component
{
    use WithPagination;
    public bool $confirmingLandingpageDeletion = false;
    public ?LandingPage $landingpageBeingDeleted = null;
    public bool $queryParamsValues = false;
    public string $queryparams = '';
    public string $final_query_urls = '';
    public ?LandingPage $idLandingpage= null;
    public $flag =0;

    public function rules(): array
    {
        return [
            'queryparams' => ['required']
        ];
    }

    public function getLandingPagesProperty()
    {
        return LandingPage::query()->latest()->paginate(10);
    }

    public function confirmLandingPageDeletion(LandingPage $landingpage): void
    {
        $this->confirmingLandingpageDeletion = true;
        $this->landingpageBeingDeleted = $landingpage;
    }


    public function deleteLandingpage(): void
    {
        if (! is_null($this->landingpageBeingDeleted)) {
            $this->landingpageBeingDeleted->delete();
        }

        $this->confirmingLandingpageDeletion = false;
    }

    public function QueryParams(LandingPage $landingpage): void
    {  
        $this->queryParamsValues = true;
        $this->idLandingpage = $landingpage;
    }

    public function addQueryParamsValue(LandingPage $landingpage,Request $request)
    {
        $this->validate();
        $params_vals = $request['serverMemo']['data']['queryparams'];
        $landingpage =$this->idLandingpage;
        $landingpage_urls= $landingpage->copiedValue();
        $landingpageUrls = explode('https://',$landingpage_urls);
        foreach($landingpageUrls as $urls){
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

    public function downloadParamsUrls(LandingPage $landingpage)
    {
        if($this->flag  == 1){
            $final_landing_urls = $this->final_query_urls;
            return response()->streamDownload(function () use ($final_landing_urls,$landingpage) {
                echo $final_landing_urls;
            }, 'Syndication QueryParams Urls ' . $landingpage->landing_template->name . '.txt');
        }
    }

    public function hasLandingpages(LandingPage $landingpage): bool
    {
        return $landingpage->landingpageConnections()->count() > 0;
    }

    public function downloadLinksFile(LandingPage $landingpage)
    {
        if (! $landingpage->content) {
            return;
        }

        return response()->streamDownload(function () use ($landingpage) {
            echo $landingpage->copiedValue();
        }, 'Syndication of ' . $landingpage->landing_template->name . '.txt');
    }

}
