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
    public bool $confirmingReset = false;
    public bool $confirmingClone = false;
    public ?EventListing $listingBeingDeleted = null;
    public $checkid = '';
    public $resetid='';
    public $cloneid='';
    public $lastcloeid = '';
    public $allCloneEmails = [];

    public function getEventlistingsProperty()
    {
        return EventListing::query()->paginate(100);
    }

    /**************** Reset Function *********/
    public function confirmingReset(EventListing $eventlisting): void
    {
        $this->confirmingReset = true;
        $this->listingBeingDeleted = $eventlisting;
        $this->resetid = $eventlisting->id;
    }


    public function resetConnection(): void
    {
        $emailIds=[];
        $eventEmail=[];
        $evenListingEmail = DB::table('eventlisting_emails')->where('event_listing_id',$this->resetid)->where('in_pool',0)->get();
        if(!$evenListingEmail->isEmpty()){
            foreach($evenListingEmail as $email){
              $emailIds[] = $email->event_email_id;
            }
        }else{
            $this->confirmingReset = false;
        }
        if(!empty($emailIds)){
            $event_email=DB::table('eventemails')->whereIn('id',$emailIds)->get();
            if(!$event_email->isEmpty()){
              foreach($event_email as $e_mail){
                $eventEmail[]=$e_mail->email;
              }
            }
        }
        $new_id_array=[];
        if(!empty($eventEmail)){
            $event_email=DB::table('eventemails')->whereIn('email',$eventEmail)->get()->toArray();
            if(!empty($event_email)){
              foreach($event_email as $unique_mail){
                $new_id_array[]=$unique_mail->id;
              }
            }
        }
        if(!empty($new_id_array)){
            DB::table('eventlisting_emails')->whereIn('event_email_id',$new_id_array)->where('event_listing_id',$this->resetid)->update(['in_pool'=>1]);
            $this->confirmingReset = false;
        }
    }

    /****************Reset Function End *********/


    /****************Clone Function Start *********/
    public function confirmingClone(EventListing $eventlisting): void
    {
        $this->confirmingClone = true;
        $this->listingBeingDeleted = $eventlisting;
        $this->cloneid = $eventlisting->id;
    }

    public function cloneConnection(): void
    {
        $event_id = $this->cloneid;
        $event = DB::table('eventlistings')->where('id',$this->cloneid)->get()->toArray();
        $all_emails = DB::table('event_invalid_emails')->pluck('email')->toArray();
        $allValidEmails=[];
        $allVaildEmailIds=[];
        $evenListingEmail = DB::table('eventemails')->where('rule_id',$this->cloneid)->pluck('email')->toArray();
        if(!empty($evenListingEmail)){
            foreach($evenListingEmail as $email){
                if (!in_array($email, $all_emails)){
                    $allValidEmails[] = $email;
                }
            }
        }
        if(!empty($allValidEmails)){
            $allVaildEmailIds = DB::table('eventemails')->whereIn('email',$allValidEmails)->pluck('id')->toArray();
        }
        if(!empty($allVaildEmailIds)){
            DB::table('eventlisting_emails')->whereIn('event_email_id',$allVaildEmailIds)->where('event_listing_id',$this->cloneid)->update(['in_pool'=>1]);
            $event_new_id = $this->cloneEvent();
            $this->clonEmails($allValidEmails);
            $this->cloneEventemail($event_new_id);
            $this->confirmingClone = false;
        }

    }
    public function cloneEvent()
    {
        $new = EventListing::find($this->cloneid);
        $new = $new->replicate();
        $new->name = $new->name . ' (Copy)';
        $new->save();
        $this->lastcloeid = $new->id;
        return $new->id;
    }


    public function clonEmails($allValidEmails)
    {
        $list=[];
        foreach($allValidEmails as $allValidEmail){
            $insertEmails = array('email' => $allValidEmail, 'sync_status' => 'no', 'rule_id' => $this->lastcloeid,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'));
            $this->allCloneEmails[] = DB::table('eventemails')->insertGetId($insertEmails);
        }
    }

    public function cloneEventemail($event_id)
    {
        $list=[];
        foreach($this->allCloneEmails as $emailid){
            $listEmails = array('event_listing_id' => $event_id,'event_email_id' => $emailid);
            DB::table('eventlisting_emails')->insert($listEmails);
        }
    }

    /****************clone function end *********/

    public function exportInPollList(EventListing $eventlisting){
        if (!$eventlisting->id) {
            return;
        }
        return response()->streamDownload(function () use ($eventlisting) {
            echo $eventlisting->copiedValue();
        }, '' . $eventlisting->name . '.csv');
    }

    public function exportNotInPollList(EventListing $eventlisting){
        if (!$eventlisting->id) {
            return;
        }
        return response()->streamDownload(function () use ($eventlisting) {
            echo $eventlisting->copiedValue();
        }, '' . $eventlisting->name . '.csv');
    }


    public function confirmingListingDeletion(EventListing $eventlisting): void
    {
        $this->confirmingListingDeletion = true;
        $this->listingBeingDeleted = $eventlisting;
        $this->checkid = $eventlisting->id;
    }



    public function deleteEventList(): void
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
