<?php

namespace App\Http\Livewire;

use App\Models\Listing;
use App\Models\Email;
use App\Models\EmailInfo;
use Livewire\Component;
use Livewire\WithPagination;
use DB;

class ListingList extends Component
{
    use WithPagination;
    public bool $confirmingListingDeletion = false;
    public ?Listing $listingBeingDeleted = null;
    public $checkid = '';
    public bool $ruleRunning = false;

    public function getListingsProperty()
    {
        return Listing::query()->paginate(100);
    }

    public function confirmingListingDeletion(Listing $listing): void
    {
        $all_lists = DB::table('listings')
                        ->leftjoin('listing_rule as lr', 'lr.listing_id','=','listings.id')
                        ->join('rules as r','r.id','=','lr.rule_id')
                        ->where('listings.id',$listing->id)
                        ->get();

        if(count($all_lists) > 0){
            foreach ($all_lists as $all_list) {
                if($all_list->status == 'running'){
                    $this->ruleRunning = true;
                    $this->confirmingListingDeletion = true;
                }else{
                    $this->ruleRunning = false;
                    $this->confirmingListingDeletion = true;
                } 
            }
        }else{
            $this->confirmingListingDeletion = true;
            $this->ruleRunning = false;
        }
        $this->listingBeingDeleted = $listing;
        $this->checkid = $listing->id;

    }

    public function deleteList(): void
    {
        $all= Listing::find($this->checkid);
        if(!empty($all->id)){
        	DB::table('listing_rule')->where('listing_id',$all->id)->delete();
        	$all_emails = DB::table('listing_email')->where('listing_id',$all->id)->pluck('email_id');
        	foreach ($all_emails as $value) {
        		Email::where('id',$value)->delete();
        		EmailInfo::where('email_id',$value)->delete();
        	}
        	DB::table('listing_email')->where('listing_id',$all->id)->delete();
    		$this->listingBeingDeleted->delete();
            $this->confirmingListingDeletion = false; 
        }
    }

    public function exportInPollList(Listing $listing){
        if (! $listing->id) {
            return;
        }
        return response()->streamDownload(function () use ($listing) {
            echo $listing->copiedValue();
        }, '' . $listing->name . '.csv');        
    }

    public function exportNotInPollList(Listing $listing){
        if (! $listing->id) {
            return;
        }
        return response()->streamDownload(function () use ($listing) {
            echo $listing->copiedValueInvalue();
        }, '' . $listing->name . '.csv');        
    }
}
