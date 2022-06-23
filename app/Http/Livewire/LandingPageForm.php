<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\LandingPage;
use App\Models\Connection;
use App\Models\LandingTemplate;
use App\Models\Tokens;
use App\Models\Profiles;
use App\Tools;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LandingPageForm extends Component
{
    public LandingPage $landingpage;
    public array $json_data = [];

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::current())->get();
    }

    public function getTemplatesProperty()
    {
        return LandingTemplate::all();
    }

     public function getTokensProperty()
    {
        return Tokens::all();
    }
     public function getProfilesProperty()
    {
        return Profiles::all();
    }

    public function mount(LandingPage $landingpage): void
    {
         $json_data_array = [];
       
        $this->landingpage = $landingpage;

         $this->landingpage->tools = Tools::current();


    }

    public function rules(): array
    {
        return [
            'landingpage.tools' => ['required', Rule::in(Tools::all())],
            'landingpage.connection_id' => ['required', 'exists:connections,id'],
            'landingpage.profile_id' => ['nullable', 'exists:token_profile,id'],
            'landingpage.landing_template_id' => ['required', 'exists:landing_templates,id'],
            'landingpage.slug' => ['required', 'string'],
            'landingpage.product' => ['nullable', 'string'],
            'landingpage.affiliate_link' => ['nullable', 'string'],
            'landingpage.name' => ['nullable', 'string'],
            'json_data.*' => ['nullable', 'string'],
        ];
    }

    public function submit(DeployPage $deployer): void
    {   
        if($this->landingpage->id == ''){
            $this->validate();
        }

        // $json_array = [];
        // $json_data = $this->json_data;
        // foreach($json_data as $k =>$v){
        //     $json_array[] = array($k => $v);
        // }
        // $this->landingpages->json_data = json_encode($json_array);
        //echo "<pre>"; print_r($this->landingpages);die;
        $this->landingpage->save();
        // $url_param = $this->landingpage->full_url;
        // $token = Tokens::get()->toArray();
        // foreach($token as $token_val){
        //     $url_params[] = $token_val['name'].'='; 
        // }

        // if(Tools::current()=="landingpage"){
        //     $final_url = $url_param.'?'.implode('&',$url_params);
        // }else{
        //     $final_url = $this->landingpage->full_url;
        // }
        $result=[];
        $profile = Profiles::where('id',$this->landingpage->profile_id)->first();

        if($profile->token_data){
            $url_parm = json_decode($profile->token_data,true);
            $url_parm = $url_parm['token_data'];
            foreach($url_parm as $key => $val){
                $result[] .=  $key . '=' . $val.'&';
            }
            $result = implode($result);
            $final_parms = substr($result,0,-1);
            //echo "<pre>"; print_r(implode($result));die;
            $finall =  $this->landingpage->full_url.'/?'.$final_parms;
        
        }else{
            $finall =  $this->landingpage->full_url.'/';
        }   
                        







        $deployer->deploylandingpage($this->landingpage);
        session()->flash('copyToClipboard', [
            'text' => 'Page link copied to clipboard',
            'value' => $finall
        ]);
        $this->redirectRoute('landingpages.index');
    }

    public function getCurrentToollProperty(){

       return Tools::current();
    }


}
