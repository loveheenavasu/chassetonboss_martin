<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventListing;
use App\Models\EventEmail;
use App\Models\EventEmailInfo;
use Livewire\WithPagination;
use DB;

class EventlistingList extends Component
{
    use WithPagination;
    public bool $confirmingListingDeletion = false;
    public ?EventListing $listingBeingDeleted = null;
    public $checkid = '';

    public function getEventlistingsProperty()
    {
        return EventListing::query()->paginate(100);
    }

    public function confirmingListingDeletion(EventListing $eventlisting): void
    {
        $this->confirmingListingDeletion = true;
        $this->listingBeingDeleted = $eventlisting;
        $this->checkid = $eventlisting->id;
    }

    public function deleteConnection(): void
    {
        $all= EventListing::find($this->checkid);
        if(!empty($all->id)){
            $all_emails = DB::table('eventlisting_emails')->where('event_listing_id',$all->id)->pluck('event_email_id');
        }
        foreach ($all_emails as $value) {
            EventEmail::where('id',$value)->delete();
            EventEmailInfo::where('event_email_id',$value)->delete();
        }
        DB::table('eventlisting_emails')->where('event_listing_id',$all->id)->delete();
        $this->listingBeingDeleted->delete();

        $this->confirmingListingDeletion = false;
    }
}
