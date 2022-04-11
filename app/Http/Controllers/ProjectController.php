<?php

namespace App\Http\Controllers;
use App\Models\ProjectListing;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projectlist.index');
    }

    public function create()
    {
        return view('projectlist.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ProjectListing $projectlist)
    {
        return view('projectlist.show', compact('projectlist'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
