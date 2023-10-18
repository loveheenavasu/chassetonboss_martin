<?php

namespace App\Http\Controllers;
use App\Models\EventPlaceholder;
use Illuminate\Http\Request;

class EventPlaceholderController extends Controller
{
    public function index()
    {
        return view('eventplaceholder.index'); 
    }

    public function create()
    {
        return view('eventplaceholder.create');
    }

    public function edit(EventPlaceholder $eventplaceholder)
    {
        return view('eventplaceholder.edit', compact('eventplaceholder'));
    }
}
