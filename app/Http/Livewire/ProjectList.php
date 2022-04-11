<?php

namespace App\Http\Livewire;
use App\Models\ProjectListing;
use App\Models\ProjectEmail;
use Livewire\Component;
use Livewire\WithPagination;
use DB;

class ProjectList extends Component
{
    use WithPagination;
    public bool $confirmingListingDeletion = false;
    public ?ProjectListing $listingBeingDeleted = null;
    public $checkid = '';
    public bool $ruleRunning = false;

    public function getProjectListProperty()
    {
        return ProjectListing::query()->paginate(100);
    }

    public function confirmingListingDeletion(ProjectListing $projectlist): void
    {
        $all_lists = DB::table('project_listings')
                        ->where('project_listings.id',$projectlist->id)
                        ->get();

        if(count($all_lists) > 0){
            $this->confirmingListingDeletion = true;
            $this->ruleRunning = false;
        }

        $this->listingBeingDeleted = $projectlist;
        $this->checkid = $projectlist->id;

    }
    public function deleteList(): void
    {
        $all= ProjectListing::find($this->checkid);
        if(!empty($all->id)){
            $all_emails = DB::table('project_listing_emails')->where('project_listing_id',$all->id)->pluck('project_email_id');
            foreach ($all_emails as $value) {
                ProjectEmail::where('id',$value)->delete();
            }
            DB::table('project_listing_emails')->where('project_listing_id',$all->id)->delete();
            $this->listingBeingDeleted->delete();
            $this->confirmingListingDeletion = false; 
        }
    }

}
