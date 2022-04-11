<?php

namespace App\Http\Controllers;
use App\Models\Event;

use Illuminate\Http\Request;

class EventCalender extends Controller
{
     public function index()
    {
        return view('eventcalender.index');
    }

    public function create()
    {
        return view('eventcalender.create');
    }

    public function edit($event_id)
    {

        $event = Event::where('id',$event_id)->first();
     
        return view('eventcalender.edit', compact('event'));
    }
}
