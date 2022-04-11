<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventListing;

class EventListingController extends Controller
{
    public function index()
    {
        return view('eventlistings.index');
    }

    public function create()
    {
        return view('eventlistings.create');
    }

    public function show(Eventlisting $eventlisting)
    {
        return view('eventlistings.show', compact('eventlisting'));
    }

    public function savenotesvalue()
    {
       $notes = $_GET['notes'];
       $id = $_GET['id'];
       $result = EventListing::where('id',$id)->first();
       if(!empty($result)){
        EventListing::where('id',$id)->update(array('notes' => $notes));
       }
    }
}
