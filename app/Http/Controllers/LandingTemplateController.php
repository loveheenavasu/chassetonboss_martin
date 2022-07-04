<?php

namespace App\Http\Controllers;
use App\Models\Page;
use App\Models\LandingPage;
use App\Actions\DeployPage;
use App\Models\LandingTemplate;
use Illuminate\Http\Request;
use App\Tools;
use App\Models\Tokens;

class LandingTemplateController extends Controller
{

    public function index()
    {
        return view('landingtemplate.index');
    }

    public function create()
    {
        return view('landingtemplate.create');
    }

    public function edit(LandingTemplate $landingtemplate)
    {
        return view('landingtemplate.edit', compact('landingtemplate'));
    }

    public function show(LandingTemplate $landingtemplate)
    {
        return view('landingpage.show', [
            'content' => $landingtemplate->content
        ]);
    }

}
