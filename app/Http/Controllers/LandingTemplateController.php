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
        $tokens_data = Tokens::get();
        return view('landingtemplate.create',compact('tokens_data'));
    }

    public function store(Request $request,DeployPage $deployer)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);
        
        $landingtemplate =new LandingTemplate;
        $landingtemplate->tool = Tools::current();
        $landingtemplate->name = $request->input('name');
        $landingtemplate->content = $request->input('content');
        $landingtemplate->html = $request->input('html');
        $landingtemplate->css = $request->input('css');
        $result =$landingtemplate->save();
        foreach ($landingtemplate->landingpages as $page) {
            $deployer->deploylandingpage($page);
        }
        

        return view('landingtemplate.index');
        //echo "<pre>"; print_r($landingtemplate);die;
    }

    public function edit(LandingTemplate $landingtemplate)
    {
        $tokens_data = Tokens::get();
        return view('landingtemplate.edit', compact('landingtemplate','tokens_data'));
    }
    public function update(Request $request,DeployPage $deployer,LandingTemplate $landingtemplate)
    { 
        // $landingtemplate =new LandingTemplate;
        // $landingtemplate->tool = Tools::current();
        // $landingtemplate->name = $request->input('name');
        // $landingtemplate->content = $request->input('content');
        // $landingtemplate->button_text = $request->input('button_text');
        // $landingtemplate->html = $request->input('html');
        // $landingtemplate->css = $request->input('css');
        $data = ([
            'tool' => Tools::current(),
            'name' => $request->input('name'),
            'content' => $request->input('content'),
            'html' => $request->input('html'),
            'css' => $request->input('css')
        ]);

        LandingTemplate::where('id', $request['id'])->update($data);
        
        $landingtemplate = LandingTemplate::find($request['id']);
        $landingInfo = $landingtemplate->landingpages()->get(); 
        
        foreach ($landingInfo as $page) {
            $deployer->deploylandingpage($page);
        }
            
        return redirect()->route('landingtemplates.index');
        // $this->redirectRoute('landingtemplates.index');
        // return view('landingtemplate.index');
    }

    public function show(LandingTemplate $landingtemplate)
    {
        return view('landingpage.show', [
            'content' => $landingtemplate->html,
            'style'=>$landingtemplate->css,
            'meta_title' => $landingtemplate->header_text,
            'link' => '#',    
        ]); 
    }
    public function getTokennnProperty(){
        return Tokens::get();
    }
   

}
