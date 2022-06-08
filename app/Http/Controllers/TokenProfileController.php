<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use DataTables;

class TokenProfileController extends Controller
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

            $allPages = Profiles::latest()->get();
           
            if(!empty($searchvalue)){
             $allPages = Profiles::orWhere('slug','like','%'.$value."%")->orWhere('id',$value)->latest(); 
            }
           
            return Datatables::of($allPages)
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('action', function ($row) {
                
                   $btn = '<a href="'.route('tokenprofile.edit', [$row->id]).'" class="open btn btn-info btn-sm">Edit</a>';
                    $btn .= '<button class="open btn btn-danger  btn-sm btn-delete" id="datatable-page" data-remote="'.$row->id.'" >Delete</a>';
                    return $btn;
                })
               ->addIndexColumn()
               ->make(true);
        }
        return view('tokenprofile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('tokenprofile.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $tokenprofiles = Profiles::findOrFail($id);
        return view('tokenprofile.edit', compact('tokenprofiles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteprofiles(Profiles $tokenprofiles){
        $pageId= $_GET['id'];
         $result = Profiles::where('id', $pageId)->delete();
        if($result){
            return true;
        }else{
            return false;
        }
        
    }
}
