<?php

namespace App\Http\Controllers;

use App\Models\Syndication;

final class SyndicationController extends Controller
{
    public function index()
    {
        return view('syndications.index');
    }

    public function create()
    {
        return view('syndications.create');
    }

    public function edit(Syndication $syndication)
    {
        return view('syndications.edit', compact('syndication'));
    }
}
