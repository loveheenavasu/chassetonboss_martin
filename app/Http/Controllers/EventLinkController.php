<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventLinkTemplate;

class EventLinkController extends Controller
{
    public function index()
    {
        return view('eventlinktemplate.index');
    }

    public function create()
    {
        return view('eventlinktemplate.create');
    }

    public function show($event_id)
    {

        $eventlinktemplate = EventLinkTemplate::where('id',$event_id)->first();
     
        return view('eventlinktemplate.edit', compact('eventlinktemplate'));
    }
}
