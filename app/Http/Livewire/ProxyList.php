<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;

use App\Models\Proxy;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;

class ProxyList extends Component
{
   public Proxy $proxy;
    use WithPagination;
    public bool $confirmingProxyDeletion = false;
    public ?int $proxyIdBeingDeleted;


    public function getProxysProperty()
    {
        return Proxy::query()->paginate(100);
    }
     public function confirmProxyDeletion($proxyId)
    {
        $this->confirmingProxyDeletion = true;
        $this->proxyIdBeingDeleted = $proxyId;
    }

    public function deleteProxy()
    {
        
        try{
            Proxy::query()->findOrNew($this->proxyIdBeingDeleted)->delete();
            
            $this->confirmingProxyDeletion = false;
            
           }catch(\Exception $e){
             $this->confirmingProxyDeletion = false;
           }
    }

}
