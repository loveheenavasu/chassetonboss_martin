<?php

namespace App\Http\Controllers;

use App\Models\Connection;

class ConnectionController extends Controller
{
    public function index()
    {
        return view('connections.index');
    }

    public function create()
    {
        return view('connections.create');
    }

    public function show(Connection $connection)
    {
        return view('connections.show', compact('connection'));
    }
}
