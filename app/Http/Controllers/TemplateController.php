<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Template;

final class TemplateController extends Controller
{
    public function index()
    {
        return view('templates.index');
    }

    public function create()
    {
        return view('templates.create');
    }

    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function show(Template $template)
    {
        return view('pages.show', [
            'content' => $template->content,
            'link' => '#',
            'button_text' => $template->button_text,
        ]);
    }
}
