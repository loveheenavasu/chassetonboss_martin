<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Actions\DeployPage;
use App\Models\Profiles;
use App\Models\Connection;
use App\Models\Template;
use App\Models\Tokens;
use App\Tools;
use Illuminate\Validation\Rule;

class ProfileValueForm extends Component
{
    //public $name;
    public array $token_data_profile = [];
    public Profiles $tokenprofiles;


    public function render()
    {
        return view('livewire.profile-value-form');
    }

    public function mount(Profiles $tokenprofiles): void
    {
        $this->tokenprofiles = $tokenprofiles;
    }
    
    public function rules(): array
    {
        return [
            'token_data_profile.*' => ['nullable', 'string'],
           //'tokenprofiles.name' => ['required', 'string'],

        ];
    }

    public function submit(): void
    {
        $token_array = [];
        $token_data = $this->token_data_profile;
        if($this->tokenprofiles->id == ''){
            foreach($token_data as $key=>$value){
                if($key == 'profile_name'){
                    continue;
                }else{
                   $json_array[$key] = $value;  
                }
                
            }
            $json = json_encode($json_array);
            $this->tokenprofiles->profile_name = $token_data['profile_name'];
            $this->tokenprofiles->token_data = $json;
            $this->tokenprofiles->save();
            $this->redirectRoute('tokenprofile.index');
        }
        else{
            $token_keys =json_decode($this->tokenprofiles['token_data']);
            $token_keys = get_object_vars($token_keys->token_data);
            foreach($token_data as $key=>$value){
                if($key == 'profile_name'){
                    continue;
                }else{
                    //$json_array[$key] = $value;
                    foreach($value as $keys=>$vals){
                        foreach($token_keys as $k=>$v){
                            if($k == $keys){
                                $token_keys[$k] = $vals; 
                            }
                        }                     
                    }
                    $json_array[$key] =$token_keys;
                }
            }
            if(isset($token_data['profile_name'])){
                $this->tokenprofiles->profile_name = $token_data['profile_name'];
            }
            if(isset($json_array)){
                $json = json_encode($json_array);
                $this->tokenprofiles->token_data = $json;
            }
            
            $this->tokenprofiles->save();
            $this->redirectRoute('tokenprofile.index');
        }
    }

    public function getTokensProperty()
    {

        return Tokens::all();
    }
}
