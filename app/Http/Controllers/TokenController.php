<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tokens;
use DataTables;

class TokenController extends Controller
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

            $allPages = Tokens::latest()->get();
           
            if(!empty($searchvalue)){
             $allPages = Tokens::orWhere('slug','like','%'.$value."%")->orWhere('id',$value)->latest(); 
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
                
                   $btn = '<a href="'.route('tokens.edit', [$row->id]).'" class="open btn btn-info btn-sm">Edit</a>';
                    $btn .= '<button class="open btn btn-danger  btn-sm btn-delete" id="datatable-page" data-remote="'.$row->id.'" >Delete</a>';
                    return $btn;
                })
               ->addIndexColumn()
               ->make(true);
        }

        return view('tokens.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tokens.create');
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
    public function edit(Tokens $token)
    {
        return view('tokens.edit', compact('token'));
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
    public function deletetoken(Tokens $token){
    
          $pageId= $_GET['id'];
         $result = Tokens::where('id', $pageId)->delete();
        if($result){
            return true;
        }else{
            return false;
        }
        
    }
}
