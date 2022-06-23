<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Profiles;
use Illuminate\Http\Request;
use DataTables;
use App\Tools;
use App\Models\Tokens;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $searchvalue = $request->search['value'];
            $val = explode('/',$searchvalue);
            if(isset($val[3])){
                $value = $val[3];
            }else{
                $value = $val[0];
            }
            $checkpage = Tools::current();
          
            if($checkpage=="landingpage"){
                $allPages = LandingPage::where('tools',$checkpage)->latest()->get(); 
            }else{
                $allPages = LandingPage::latest()->get(); 
            }
            
            if(!empty($searchvalue)){
             $allPages = LandingPage::orWhere('slug','like','%'.$value."%")->orWhere('id',$value)->latest(); 
            }
            
            return Datatables::of($allPages)
                ->addColumn('id', function ($row) {
                    return $row->id;
                })

                ->addColumn('full_url', function ($row) {
                    if(Tools::current()=="landingpage"){
                        // if(isset($row->profile_id) && $row->profile_id >0){
                        //     $result = [];
                        //     $profile = Profiles::where('id',$row->profile_id)->first();
                        //     if($profile){
                        //         $url_parm = json_decode($profile->token_data,true);
                        //         $url_parm = $url_parm['token_data'];
                        //         foreach($url_parm as $key => $val){
                        //             $result[] .=  $key . '=' . $val.'&';
                        //         }
                        //         $result = implode($result);
                        //         $final_parms = substr($result,0,-1);
                        //         //echo "<pre>"; print_r(implode($result));die;
                        //         $finall =  $row->full_url.'/?'.$final_parms;
                              
                        //     }else{
                                $finall =  $row->full_url.'/';
                            //}   
                        return $finall;  
                    //}
                }
                    
                })
                ->addColumn('profile_id', function ($row) {
                    $profile = Profiles::where('id',$row->profile_id)->first();
                    if($profile){
                        return $profile->profile_name;
                    }
                    else{
                        return $profile=null;
                    }
                    
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('action', function ($row) {
                    if(Tools::current()=="landingpage"){
                        if(isset($row->profile_id) && $row->profile_id >0){
                            $result = [];
                            $profile = Profiles::where('id',$row->profile_id)->first();
                            if($profile){
                                $url_parm = json_decode($profile->token_data,true);
                                $url_parm = $url_parm['token_data'];
                                foreach($url_parm as $key => $val){
                                    $result[] .=  $key . '=' . $val.'&';
                                }
                                $result = implode($result);
                                $final_parms = substr($result,0,-1);
                                $finall =  $row->full_url.'/?'.$final_parms;
                              
                            }else{
                                $final_parms = '';
                                $finall =  $row->full_url.'/';
                            }
                        }   
                        
                    }

                    $btn = '<input type="text" id="copy_to_clipboard_'.$row->id.'" value="'.$finall.'" style="opacity: 0;"/><a onclick="copyToClipboard('.$row->id.')" class="copy btn btn btn-secondary btn-sm focus:outline-none">Copy link</a>';
                    $btn .= '<a href="'.$finall.'" target="_blank" class="open btn btn-success btn-sm">Open</a>';
                    $btn .= '<a href="'.route('landingpages.edit', ['landingpage' => $row->id]).'" class="open btn btn-info btn-sm">Edit</a>';
                   $btn .= '<input type="text" id="copy_url_param'.$row->id.'" value="?'.$final_parms.'" style="opacity: 0;    margin-left: -8px;"/><a onclick="copyParmasToClipboard('.$row->id.')" class="copy_params btn btn-primary btn-sm focus:outline-none">Copy Url Parameters</a>';
                    $btn .= '<button class="open btn btn-danger  btn-sm btn-delete" id="datatable-page" data-remote="'.$row->id.'" >Delete</a>';
                    return $btn;
                })
               ->addIndexColumn()
               ->make(true);
        }
        return view('landingpage.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('landingpage.create');
    }

    public function edit(LandingPage $landingpage)
    {
       return view('landingpage.edit', compact('landingpage'));
    }

    
    public function deletelandingpage(LandingPage $landingpage)
    { 
        $pageId= $_GET['id'];
        $result = LandingPage::where('id', $pageId)->delete();
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
