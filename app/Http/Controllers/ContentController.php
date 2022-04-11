<?php

namespace App\Http\Controllers;

use App\Models\Content;

final class ContentController extends Controller
{
    public function index()
    {
        return view('contents.index');
    }

    public function create()
    {
        return view('contents.create');
    }

    public function edit(Content $content)
    {
        return view('contents.edit', compact('content'));
    }
}
