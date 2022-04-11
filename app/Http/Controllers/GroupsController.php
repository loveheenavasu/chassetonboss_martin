<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\GmailConnection;

class GroupsController extends Controller
{
    public function index()
    {
        return view('groups.index');
    }

    public function create()
    {
        return view('groups.create');
    }

    public function edit(Groups $group)
    {
        return view('groups.edit', compact('group'));
    }
    public function selectgroup()
    {
        $getselectedvalue=$_GET['getselectedvalue'];
        $groupame=$_GET['groupName'];
        $accounts = count($getselectedvalue);
        //print_r($accounts);die;
        $values = array( 
                        'name'     => $groupame,
                        'accounts' => $accounts
                        );
        $g_id = Groups::create($values)->id;
        //foreach ($getselectedvalue as $key => $value) {
            
        
    }

}
