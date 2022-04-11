<?php

namespace App\Http\Livewire;

use App\Tools;
use App\Models\GmailConnection;
use App\Models\Groups;
use App\Models\ProjectListing;
use App\Models\Proxy;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GmailConnectionForm extends Component
{
    public GmailConnection $gmailconnection;
    
    public function render()
    {
        return view('livewire.gmail-connection-form');
    }



    public function rules(): array
    {
        return [
            'gmailconnection.email_id' => ['required', 'email','unique:gmail_connections,email_id'],
            'gmailconnection.project_listing_id' => ['nullable'],
            'gmailconnection.group_id' => ['nullable'],
        ];
    }

    public function mount(GmailConnection $gmailconnection)
    {

        $this->gmailconnection = $gmailconnection;

    }
    public function getGroupsProperty(){

        return Groups::get();

    }
    public function getProjectListingsProperty(){

        return ProjectListing::get();

    }
    public function getProxyProperty(){

        return Proxy::get();
        
    }

    public function submit(): void
    {
        if($this->gmailconnection->id == ''){
            $this->validate();
        }

        $this->gmailconnection->save();

        $this->redirectRoute('gmailconnection.index');
    }
}
