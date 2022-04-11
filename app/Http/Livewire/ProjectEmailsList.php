<?php

namespace App\Http\Livewire;

use App\Models\ProjectListing;
use App\Models\ProjectEmail;
use App\Models\ProjectListingEmail;
use Livewire\Component;

class ProjectEmailsList extends Component
{
    public ProjectListing $projectlist;

    public function getProjectEmailsProperty()
    {
        $all_email_id = ProjectListingEmail::where('project_listing_id',$this->            projectlist->id)
                        ->pluck('project_email_id')->toArray();
        $allEmails = ProjectEmail::whereIn('id',$all_email_id)->get();  
        return $allEmails;
    }

    public function mount(ProjectListing $projectlist): void
    {
        if (!$projectlist->exists) {
            throw new \InvalidArgumentException('Project Listing model must exist in database.');
        }

        $this->projectlist = $projectlist;
    }

}
