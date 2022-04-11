<?php

namespace App\Http\Controllers;
use App\Models\Page;
use App\Models\PremiumPages;
use App\Models\PremiumTemplates;
use Illuminate\Http\Request;

class PremiumTemplatesController extends Controller
{
   public function index()
    {
        return view('premiumtemplates.index');
    }

    public function create()
    {
        return view('premiumtemplates.create');
    }

    public function edit(PremiumTemplates $premiumtemplate)
    {

        return view('premiumtemplates.edit', compact('premiumtemplate'));
    }

    public function show(PremiumTemplates $premiumtemplate)
    {
        return view('premiumpages.show', [
            'content' => $premiumtemplate->content,
            'meta_title' => $premiumtemplate->header_text,
            'link' => '#',
            'button_text' => $premiumtemplate->button_text,
            
        ]);
    }
}
