<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Listing;
use DB;

class ListingController extends Controller
{
    public function index()
    {
        return view('listings.index');
    }

    public function create()
    {
        return view('listings.create');
    }

    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }
    public function savenotesvalue()
    {
       $notes = $_GET['notes'];
       $id = $_GET['id'];
       $result = Listing::where('id',$id)->first();
       if(!empty($result)){
        Listing::where('id',$id)->update(array('notes' => $notes));
       }
    }

    public function deletelist(Request $request)
    {
        $ids = $request->ids;
        $response = [];
        
        $all_list_table_data =Listing::whereIn('id',explode(',',$ids))->get()->toArray(); 
    
        foreach($all_list_table_data as $overall_list ){
            $all_lists = DB::table('listings')
                        ->leftjoin('listing_rule as lr', 'lr.listing_id','=','listings.id')
                        ->join('rules as r','r.id','=','lr.rule_id')
                        ->where('listings.id',$overall_list['id'])
                        ->select('listings.name as list_name','r.status as status','listings.id as list_id','r.id as rule_id','r.name as rule_name')
                        ->get()->toArray();
            if(count($all_lists) > 0){
                foreach ($all_lists as $all_list) {
                    if($all_list->status == 'running'){
                        
                        $response[]['status'] = 'running';
                    }
                    else if($all_list->status != 'running'){
                        $result = Listing::where('id',$all_list->list_id)->delete();
                        if($result){
                            
                            $response[]['status'] = 'true';
                        }   
                    }
                 
                }         
            }
            else{
                $result = Listing::where('id',$overall_list['id'])->delete();
                $response[]['status'] = 'true';
                
            }
        }
        
        echo json_encode($response);
        
    }
}
