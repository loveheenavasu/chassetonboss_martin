<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Actions\DeployPage;
use App\Models\Page;
use App\Models\Connection;
use App\Models\Template;
use App\Models\Tokens;
use App\Models\TokenValue;
use App\Tools;
use Illuminate\Validation\Rule;


class TokenValueForm extends Component
{
    public $name;
    public TokenValue $tokenvalue;
    public array $token_data = [];

    public function render()
    {
        return view('livewire.token-value-form');
    }
    public function mount(TokenValue $tokenvalue): void
    {
       

        $this->tokenvalue = $tokenvalue;

    }
     public function rules(): array
    {

        return [
            
           'token_data.*' => ['nullable', 'string'],

        ];
    } 
    public function submit(): void
    {

        //$this->validate();
        $json_array = [];
      
        $json_data = $this->token_data['name'];
        
        foreach($json_data as $k =>$v){

            $json_array[] = array($k => $v);
        }
        $result = TokenValue::get();
        if(count($result)==0){
            $this->tokenvalue->name = json_encode($json_array);
            $this->tokenvalue->save();
        }
        else{
            $name_values = json_encode($json_array);
            $tokens_name = TokenValue::where('id',1)->update([
                'name'=>$name_values
            ]);
        }
        
        // $name_values = json_encode($json_array);
    
        // $tokens_name = TokenValue::where('id',1)->updateOrCreate([
        //     'name'=>$name_values
        // ]);
        
        //$this->tokenvalue->save();
        $this->redirectRoute('tokensvalue.index');
    }
     public function getTokensProperty()
    {
        return Tokens::all();
    }


}
