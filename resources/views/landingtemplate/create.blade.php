    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.18.4/css/grapes.min.css"/>
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/demos.css?v3">
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/toastr.min.css">
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/grapes.min.css?v0.19.4">
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/grapesjs-preset-webpage.min.css">
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/tooltip.css">
    <link rel="stylesheet" href="https://grapesjs.com/stylesheets/grapesjs-plugin-filestack.css">
    
    <link href="https://unpkg.com/grapick/dist/grapick.min.css" rel="stylesheet">

    <!-- <script src="//static.filestackapi.com/v3/filestack.js"></script> -->
    <!-- <script src="js/aviary.js"></script> old //feather.aviary.com/imaging/v3/editor.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://grapesjs.com/js/toastr.min.js"></script>

    <script src="https://grapesjs.com/js/grapes.min.js?v0.19.4"></script>
    <script src="https://grapesjs.com/js/grapesjs-preset-webpage.min.js?v0.1.11"></script>
    <script src="https://grapesjs.com/js/grapesjs-lory-slider.min.js?0.1.5"></script>
    <script src="https://grapesjs.com/js/grapesjs-tabs.min.js?0.1.1"></script>
    <script src="https://grapesjs.com/js/grapesjs-custom-code.min.js?0.1.2"></script>
    <!-- <script src="https://grapesjs.com/js/grapesjs-touch.min.js?0.1.1"></script> -->
    <script src="https://grapesjs.com/js/grapesjs-parser-postcss.min.js?0.1.1"></script>
    <script src="https://grapesjs.com/js/grapesjs-tooltip.min.js?0.1.1"></script>
    <script src="https://grapesjs.com/js/grapesjs-tui-image-editor.min.js?0.1.2"></script>
    <script src="https://grapesjs.com/js/grapesjs-typed.min.js?1.0.5"></script>
    <script src="https://grapesjs.com/js/grapesjs-style-bg.min.js?1.0.1"></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Landing template') }}
            </h2>

            <a href="{{ route('landingtemplates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Go Back</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <form class="landing-template-form" action="{{ route('landingtemplates.store') }}" method="POST">
            @csrf 
            <div class="shadow overflow-hidden sm:rounded-md">  
            <div class="px-4 py-5 bg-white sm:p-6 landing-template-form-sec">
                <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">Name </label>
                    <input id="name" type="text" class="form-input rounded-md shadow-sm mt-1 block w-full" name="name"/>
                    @if ($errors->has('name'))
                        <span class="text-red-500">
                            {{ $errors->first('name') }}
                        </span>
                    @endif
                </div>
                <div class="col-span-6">
                    <label for="content" class="block font-medium text-sm text-gray-700">Editor</label>
                    <div>
                        <div class="gjs-logo-cont">
                            <a href="https://grapesjs.com"><img class="gjs-logo" src="https://grapesjs.com/img/grapesjs-logo-cl.png"></a>
                            <div class="gjs-logo-version"></div>
                        </div>
                    </div>
                    <div id="gjs">
                          

                        <style>
                            
                            .clearfix{ clear:both}
                            .header-banner{
                              padding-top: 35px;
                              padding-bottom: 100px;
                              color: #ffffff;
                              font-family: Helvetica, serif;
                              font-weight: 100;
                              background-image:url("https://grapesjs.com/img/bg-gr-v.png"), url("https://grapesjs.com/img/work-desk.jpg");
                              background-attachment:scroll, scroll;
                              background-position:left top, center center;
                              background-repeat:repeat-y, no-repeat;
                              background-size: contain, cover;
                            }
                            .container-width{
                              width: 90%;
                              max-width: 1150px;
                              margin: 0 auto;
                            }
                            .logo-container{
                              float: left;
                              width: 50%;
                            }
                            .logo{
                              background-color: #fff;
                              border-radius: 5px;
                              width: 130px;
                              padding: 10px;
                              min-height: 30px;
                              text-align: center;
                              line-height: 30px;
                              color: #4d114f;
                              font-size: 23px;
                            }
                            .menu {
                              float: right;
                              width: 50%;
                            }
                            .menu-item{
                              float:right;
                              font-size: 15px;
                              color:#eee;
                              width: 130px;
                              padding: 10px;
                              min-height: 50px;
                              text-align: center;
                              line-height: 30px;
                              font-weight: 400;
                            }
                            .lead-title{
                              margin: 150px 0 30px 0;
                              font-size: 40px;
                            }
                            .sub-lead-title{
                              max-width: 650px;
                              line-height:30px;
                              margin-bottom:30px;
                              color: #c6c6c6;
                            }
                            .lead-btn{
                              margin-top: 15px;
                              padding:10px;
                              width:190px;
                              min-height:30px;
                              font-size:20px;
                              text-align:center;
                              letter-spacing:3px;
                              line-height:30px;
                              background-color:#d983a6;
                              border-radius:5px;
                              transition: all 0.5s ease;
                              cursor: pointer;
                            }
                            .lead-btn:hover{
                              background-color:#ffffff;
                              color:#4c114e;
                            }
                            .lead-btn:active{
                              background-color:#4d114f;
                              color:#fff;
                            }
                            .flex-sect{
                              background-color: #fafafa;
                              padding: 100px 0;
                              font-family: Helvetica, serif;
                            }
                            .flex-title{
                              margin-bottom: 15px;
                              font-size: 2em;
                              text-align: center;
                              font-weight: 700;
                              color:#555;
                              padding: 5px;
                            }
                            .flex-desc{
                              margin-bottom: 55px;
                              font-size: 1em;
                              color: rgba(0, 0, 0, 0.5);
                              text-align: center;
                              padding: 5px;
                            }
                            .cards{
                              padding: 20px 0;
                              display: flex;
                              justify-content: space-around;
                              flex-flow: wrap;
                            }
                            .card{
                              background-color: white;
                              height: 300px;
                              width:300px;
                              margin-bottom:30px;
                              box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
                              border-radius: 2px;
                              transition: all 0.5s ease;
                              font-weight: 100;
                              overflow: hidden;
                            }
                            .card:hover{
                              margin-top: -5px;
                              box-shadow: 0 20px 30px 0 rgba(0, 0, 0, 0.2);
                            }
                            .card-header{
                              height: 155px;
                              background-image:url("https://via.placeholder.com/350x250/78c5d6/fff/image1.jpg");
                              background-size:cover;
                              background-position:center center;
                            }
                            .card-header.ch2{
                              background-image:url("https://via.placeholder.com/350x250/459ba8/fff/image2.jpg");
                            }
                            .card-header.ch3{
                              background-image:url("https://via.placeholder.com/350x250/79c267/fff/image3.jpg");
                            }
                            .card-header.ch4{
                              background-image:url("https://via.placeholder.com/350x250/c5d647/fff/image4.jpg");
                            }
                            .card-header.ch5{
                              background-image:url("https://via.placeholder.com/350x250/f28c33/fff/image5.jpg");
                            }
                            .card-header.ch6{
                              background-image:url("https://via.placeholder.com/350x250/e868a2/fff/image6.jpg");
                            }
                            .card-body{
                              padding: 15px 15px 5px 15px;
                              color: #555;
                            }
                            .card-title{
                              font-size: 1.4em;
                              margin-bottom: 5px;
                            }
                            .card-sub-title{
                              color: #b3b3b3;
                              font-size: 1em;
                              margin-bottom: 15px;
                            }
                            .card-desc{
                              font-size: 0.85rem;
                              line-height: 17px;
                            }
                            .am-sect{
                              padding-top: 100px;
                              padding-bottom: 100px;
                              font-family: Helvetica, serif;
                            }
                            .img-phone{
                              float: left;
                            }
                            .am-container{
                              display: flex;
                              flex-wrap: wrap;
                              align-items: center;
                              justify-content: space-around;
                            }*/
                            /*
                            .am-container{
                              perspective: 1000px;
                            }*/
                            .am-content{
                              float:left;
                              padding:7px;
                              width: 490px;
                              color: #444;
                              font-weight: 100;
                              margin-top: 50px;
                            }
                            .am-pre{
                              padding:7px;
                              color:#b1b1b1;
                              font-size:15px;
                            }
                            .am-title{
                              padding:7px;
                              font-size:25px;
                              font-weight: 400;
                            }
                            .am-desc{
                              padding:7px;
                              font-size:17px;
                              line-height:25px;
                            }
                            .am-post{
                              padding:7px;
                              line-height:25px;
                              font-size:13px;
                            }
                            .blk-sect{
                              padding-top: 100px;
                              padding-bottom: 100px;
                              background-color: #222222;
                              font-family: Helvetica, serif;
                            }
                            .blk-title{
                              color:#fff;
                              font-size:25px;
                              text-align:center;
                              margin-bottom: 15px;
                            }
                            .blk-desc{
                              color:#b1b1b1;
                              font-size:15px;
                              text-align:center;
                              max-width:700px;
                              margin:0 auto;
                              font-weight:100;
                            }
                            .price-cards{
                              margin-top: 70px;
                              display: flex;
                              flex-wrap: wrap;
                              align-items: center;
                              justify-content: space-around;
                            }
                            .price-card-cont{
                              width: 300px;
                              padding: 7px;
                              float:left;
                            }
                            .price-card{
                              margin:0 auto;
                              min-height:350px;
                              background-color:#d983a6;
                              border-radius:5px;
                              font-weight: 100;
                              color: #fff;
                              width: 90%;
                            }
                            .pc-title{
                              font-weight:100;
                              letter-spacing:3px;
                              text-align:center;
                              font-size:25px;
                              background-color:rgba(0, 0, 0, 0.1);
                              padding:20px;
                            }
                            .pc-desc{
                              padding: 75px 0;
                              text-align: center;
                            }
                            .pc-feature{
                              color:rgba(255,255,255,0.5);
                              background-color:rgba(0, 0, 0, 0.1);
                              letter-spacing:2px;
                              font-size:15px;
                              padding:10px 20px;
                            }
                            .pc-feature:nth-of-type(2n){
                              background-color:transparent;
                            }
                            .pc-amount{
                              background-color:rgba(0, 0, 0, 0.1);
                              font-size: 35px;
                              text-align: center;
                              padding: 35px 0;
                            }
                            .pc-regular{
                              background-color: #da78a0;
                            }
                            .pc-enterprise{
                              background-color: #d66a96;
                            }
                            .footer-under{
                              background-color: #312833;
                              padding-bottom: 100px;
                              padding-top: 100px;
                              min-height: 500px;
                              color:#eee;
                              position: relative;
                              font-weight: 100;
                              font-family: Helvetica,serif;
                            }
                            .led{
                              border-radius: 100%;
                              width: 10px;
                              height: 10px;
                              background-color: rgba(0, 0, 0, 0.15);
                              float: left;
                              margin: 2px;
                              transition: all 5s ease;
                            }
                            .led:hover{
                              background-color: #c29fca;/* #eac229 
                              box-shadow: 0 0 5px #9d7aa5, 0 0 10px #e6c3ee;/* 0 0 10px 0 #efc111 */
                              transition: all 0s ease;
                            }
                            .copyright {
                              background-color: rgba(0, 0, 0, 0.15);
                              color: rgba(238, 238, 238, 0.5);
                              bottom: 0;
                              padding: 1em 0;
                              position: absolute;
                              width: 100%;
                              font-size: 0.75em;
                            }
                            .made-with{
                              float: left;
                              width: 50%;
                              padding: 5px 0;
                            }
                            .foot-social-btns{
                              display: none;
                              float: right;
                              width: 50%;
                              text-align: right;
                              padding: 5px 0;
                            }
                            .footer-container{
                              display: flex;
                              flex-wrap: wrap;
                              align-items: stretch;
                              justify-content: space-around;
                            }
                            .foot-list {
                              float: left;
                              width: 200px;
                            }
                            .foot-list-title {
                              font-weight: 400;
                              margin-bottom: 10px;
                              padding: 0.5em 0;
                            }
                            .foot-list-item {
                              color: rgba(238, 238, 238, 0.8);
                              font-size: 0.8em;
                              padding: 0.5em 0;
                            }
                            .foot-list-item:hover {
                              color: rgba(238, 238, 238, 1);
                            }
                            .foot-form-cont{
                              width: 300px;
                              float: right;
                            }
                            .foot-form-title{
                              color: rgba(255,255,255,0.75);
                              font-weight: 400;
                              margin-bottom: 10px;
                              padding: 0.5em 0;
                              text-align: right;
                              font-size: 2em;
                            }
                            .foot-form-desc{
                              font-size: 0.8em;
                              color: rgba(255,255,255,0.55);
                              line-height: 20px;
                              text-align: right;
                              margin-bottom: 15px;
                            }

                            .form {
                              border-radius: 3px;
                              padding: 10px 15px;
                              background-color: rgba(0,0,0,0.2);
                            }

                            .input,
                            .textarea,
                            .select,
                            .sub-input {
                              width: 100%;
                              margin-bottom: 15px;
                              padding: 7px 10px;
                              border-radius: 2px;
                              color:#fff;
                              background-color: #554c57;
                              border: none;
                            }

                            .select {
                              height: 30px;
                            }

                            .label {
                              width: 100%;
                              display: block;
                            }

                            .button,
                            .sub-btn{
                              width: 100%;
                              margin: 15px 0;
                              background-color: #785580;
                              border: none;
                              color:#fff;
                              border-radius: 2px;
                              padding: 7px 10px;
                              font-size: 1em;
                              cursor: pointer;
                            }
                            .sub-btn:hover{
                              background-color: #91699a;
                            }
                            .sub-btn:active{
                              background-color: #573f5c;
                            }
                            .blk-row::after{
                              content: "";
                              clear: both;
                              display: block;
                            }
                            .blk-row{
                              padding: 10px;
                            }
                            .blk1{
                              width: 100%;
                              padding: 10px;
                              min-height: 75px;
                            }
                            .blk2{
                              float: left;
                              width: 50%;
                              padding: 10px;
                              min-height: 75px;
                            }
                            .blk3{
                              float: left;
                              width: 33.3333%;
                              padding: 10px;
                              min-height: 75px;
                            }
                            .blk37l{
                              float: left;
                              width: 30%;
                              padding: 10px;
                              min-height: 75px;
                            }
                            .blk37r{
                              float: left;
                              width: 70%;
                              padding: 10px;
                              min-height: 75px;
                            }
                            .heading{padding: 10px;}
                            .paragraph{padding: 10px;}

                            .bdg-sect{
                              padding-top:100px;
                              padding-bottom:100px;
                              font-family: Helvetica, serif;
                              background-color: #fafafa;
                            }
                            .bdg-title{
                              text-align: center;
                              font-size: 2em;
                              margin-bottom: 55px;
                              color: #555555;
                            }
                            .badges{
                              padding:20px;
                              display: flex;
                              justify-content: space-around;
                              align-items: flex-start;
                              flex-wrap: wrap;
                            }
                            .badge{
                              width: 290px;
                              font-family: Helvetica, serif;
                              background-color: white;
                              margin-bottom:30px;
                              box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2);
                              border-radius: 3px;
                              font-weight: 100;
                              overflow: hidden;
                              text-align: center;
                            }
                            .badge-header{
                              height: 115px;
                              background-image:url("https://grapesjs.com/img/bg-gr-v.png"), url("https://grapesjs.com/img/work-desk.jpg");
                              background-position:left top, center center;
                              background-attachment:scroll, fixed;
                              overflow: hidden;
                            }
                            .blurer{
                              filter: blur(5px);
                            }
                            .badge-name{
                              font-size: 1.4em;
                              margin-bottom: 5px;
                            }
                            .badge-role{
                              color: #777;
                              font-size: 1em;
                              margin-bottom: 25px;
                            }
                            .badge-desc{
                              font-size: 0.85rem;
                              line-height: 20px;
                            }
                            .badge-avatar{
                              width:100px;
                              height:100px;
                              border-radius: 100%;
                              border: 5px solid #fff;
                              box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
                              margin-top: -75px;
                              position: relative;
                            }
                            .badge-body{
                              margin: 35px 10px;
                            }
                            .badge-foot{
                              color:#fff;
                              background-color:#a290a5;
                              padding-top:13px;
                              padding-bottom:13px;
                              display: flex;
                              justify-content: center;
                            }
                            .badge-link{
                              height: 35px;
                              width: 35px;
                              line-height: 35px;
                              font-weight: 700;
                              background-color: #fff;
                              color: #a290a5;
                              display: block;
                              border-radius: 100%;
                              margin: 0 10px;
                            }
                            .quote{
                              color:#777;
                              font-weight: 300;
                              padding: 10px;
                              box-shadow: -5px 0 0 0 #ccc;
                              font-style: italic;
                              margin: 20px 30px;
                            }

                            @media (max-width: 768px){
                              .foot-form-cont{
                                width:400px;
                              }
                              .foot-form-title{
                                width:autopx;
                              }
                            }

                            @media (max-width: 480px){
                              .foot-lists{
                                display:none;
                              }
                            }
                        </style>
                    </div>
                    <p class="mt-3 mb-3 text-sm text-gray-600">{{ __('Available placeholders') }}:</p>
                      <ul>
                        @foreach($tokens_data as $single)
                          <li>{{$single->name}}</li>
                          @endforeach
                      </ul>
                </div>
                <div class="col-span-6 sm:col-span-3">
                     <input id="content" type="hidden" class="mt-1 block w-full" name="content" />
                    <input id="html" type="hidden" class="mt-1 block w-full" name="html" />
                    <input id="css" type="hidden" class="mt-1 block w-full" name="css" />

                </div>
              </div>
            </div>
                <div class="flex items-center justify-end px-4 py-4 bg-gray-50 text-right sm:px-6">
            
                    <button type="submit" name="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Create</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

    <style>
       .landing-template-form .landing-template-form-sec {
            padding: 48px 24px;
        }
        .landing-template-form-sec label {
            margin-bottom: 8px;
        }
        body, html {
            overflow: auto;
            overflow-x: hidden;
        }
        .landing-template-form .gjs-editor-cont {
            height: 800px !important;
        }
        .landing-template-form .gjs-editor-cont .gjs-pn-panel.gjs-pn-views-container.gjs-one-bg.gjs-two-color{
                height: 800px;
        }
        .icon-add-comp::before, .icon-comp100::before,.icon-comp50::before,.icon-comp30::before,.icon-rm::before{ content: '';}
        .icon-add-comp {
          background: url("https://grapesjs.com/img/icon-sq-a.png") no-repeat center;
        }
        .icon-comp100 {
          background: url("https://grapesjs.com/img/icon-sq-1.png") no-repeat center;
        }
        .icon-comp50 {
          background: url("https://grapesjs.com/img/icon-sq-2.png") no-repeat center;
        }
        .icon-comp30 {
          background: url("https://grapesjs.com/img/icon-sq-3.png") no-repeat center;
        }
        .icon-rm {
          background: url("https://grapesjs.com/img/icon-sq-r.png") no-repeat center;
        }

        .icons-flex {
          background-size: 70% 65% !important;
          height: 15px;
          width: 17px;
          opacity: 0.9;
        }
        .icon-dir-row {
          background: url("https://grapesjs.com/img/flex-dir-row.png") no-repeat center;
        }
        .icon-dir-row-rev {
          background: url("https://grapesjs.com/img/flex-dir-row-rev.png") no-repeat center;
        }
        .icon-dir-col {
          background: url("https://grapesjs.com/img/flex-dir-col.png") no-repeat center;
        }
        .icon-dir-col-rev {
          background: url("https://grapesjs.com/img/flex-dir-col-rev.png") no-repeat center;
        }
        .icon-just-start{
         background: url("https://grapesjs.com/img/flex-just-start.png") no-repeat center;
        }
        .icon-just-end{
         background: url("https://grapesjs.com/img/flex-just-end.png") no-repeat center;
        }
        .icon-just-sp-bet{
         background: url("https://grapesjs.com/img/flex-just-sp-bet.png") no-repeat center;
        }
        .icon-just-sp-ar{
         background: url("https://grapesjs.com/img/flex-just-sp-ar.png") no-repeat center;
        }
        .icon-just-sp-cent{
         background: url("https://grapesjs.com/img/flex-just-sp-cent.png") no-repeat center;
        }
        .icon-al-start{
         background: url("https://grapesjs.com/img/flex-al-start.png") no-repeat center;
        }
        .icon-al-end{
         background: url("https://grapesjs.com/img/flex-al-end.png") no-repeat center;
        }
        .icon-al-str{
         background: url("https://grapesjs.com/img/flex-al-str.png") no-repeat center;
        }
        .icon-al-center{
         background: url("https://grapesjs.com/img/flex-al-center.png") no-repeat center;
        }

         [data-tooltip]::after {
           background: rgba(51, 51, 51, 0.9);
         }

         .gjs-pn-commands {
           min-height: 40px;
         }

         #gjs-sm-float,
         .gjs-pn-views .fa-cog {
            display: none;
         }

         .gjs-am-preview-cont {
           height: 100px;
           width: 100%;
         }

         .gjs-logo-version {
           background-color: #756467;
         }

        .gjs-pn-panel.gjs-pn-views {
          padding: 0;
          border-bottom: none;
        }

        .gjs-pn-btn.gjs-pn-active {
          box-shadow: none;
        }

        .gjs-pn-views .gjs-pn-btn {
            margin: 0;
            height: 40px;
            padding: 10px;
            width: 33.3333%;
            border-bottom: 2px solid rgba(0, 0, 0, 0.3);
        }

        .CodeMirror {
          min-height: 450px;
          margin-bottom: 8px;
        }
        .grp-handler-close {
          background-color: transparent;
          color: #ddd;
        }

        .grp-handler-cp-wrap {
          border-color: transparent;
        }

    </style>
    
    <script type="text/javascript">
      localStorage.clear();
      var lp = 'https://grapesjs.com/img/';
      var plp = 'https://via.placeholder.com/350x250/';
      var images = [
        lp+'team1.jpg', lp+'team2.jpg', lp+'team3.jpg', plp+'78c5d6/fff/image1.jpg', plp+'459ba8/fff/image2.jpg', plp+'79c267/fff/image3.jpg',
        plp+'c5d647/fff/image4.jpg', plp+'f28c33/fff/image5.jpg', plp+'e868a2/fff/image6.jpg', plp+'cc4360/fff/image7.jpg',
        lp+'work-desk.jpg', lp+'phone-app.png', lp+'bg-gr-v.png'
      ];

      var editor  = grapesjs.init({
        avoidInlineStyle: false,
        height: '100%',
        container : '#gjs',
        fromElement: 1,
        showOffsets: 1,
        assetManager: {
          embedAsBase64: 1,
          assets: images
        },
        selectorManager: { componentFirst: true },
        styleManager: { sectors: [] },
        plugins: [
          'grapesjs-lory-slider',
          'grapesjs-tabs',
          'grapesjs-custom-code',
          'grapesjs-touch',
          'grapesjs-parser-postcss',
          'grapesjs-tooltip',
          'grapesjs-tui-image-editor',
          'grapesjs-typed',
          'grapesjs-style-bg',
          'gjs-preset-webpage',
        ],
        pluginsOpts: {
          'grapesjs-tui-image-editor': {
            script: [
              // 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.min.js',
              'https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js',
              'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js',
              'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js'
            ],
            style: [
              'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css',
              'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css',
            ],
          },
          'grapesjs-lory-slider': {
            sliderBlock: {
              category: 'Extra'
            }
          },
          'grapesjs-tabs': {
            tabsBlock: {
              category: 'Extra'
            }
          },
          'grapesjs-typed': {
            block: {
              category: 'Extra',
              content: {
                type: 'typed',
                'type-speed': 40,
                strings: [
                  'Text row one',
                  'Text row two',
                  'Text row three',
                ],
              }
            }
          },
          'gjs-preset-webpage': {
            modalImportTitle: 'Import Template',
            modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
            modalImportContent: function(editor) {
              return editor.getHtml() + '<style>'+editor.getCss()+'</style>'
            },
            filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
            aviaryOpts: false,
            blocksBasicOpts: { flexGrid: 1 },
            customStyleManager: [{
              name: 'General',
              properties:[
                {
                  extend: 'float',
                  type: 'radio',
                  default: 'none',
                  options: [
                    { value: 'none', className: 'fa fa-times'},
                    { value: 'left', className: 'fa fa-align-left'},
                    { value: 'right', className: 'fa fa-align-right'}
                  ],
                },
                'display',
                { extend: 'position', type: 'select' },
                'top',
                'right',
                'left',
                'bottom',
              ],
            }, {
                name: 'Dimension',
                open: false,
                properties: [
                  'width',
                  {
                    id: 'flex-width',
                    type: 'integer',
                    name: 'Width',
                    units: ['px', '%'],
                    property: 'flex-basis',
                    toRequire: 1,
                  },
                  'height',
                  'max-width',
                  'min-height',
                  'margin',
                  'padding'
                ],
              },{
                name: 'Typography',
                open: false,
                properties: [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'letter-spacing',
                    'color',
                    'line-height',
                    {
                      extend: 'text-align',
                      options: [
                        { id : 'left',  label : 'Left',    className: 'fa fa-align-left'},
                        { id : 'center',  label : 'Center',  className: 'fa fa-align-center' },
                        { id : 'right',   label : 'Right',   className: 'fa fa-align-right'},
                        { id : 'justify', label : 'Justify',   className: 'fa fa-align-justify'}
                      ],
                    },
                    {
                      property: 'text-decoration',
                      type: 'radio',
                      default: 'none',
                      options: [
                        { id: 'none', label: 'None', className: 'fa fa-times'},
                        { id: 'underline', label: 'underline', className: 'fa fa-underline' },
                        { id: 'line-through', label: 'Line-through', className: 'fa fa-strikethrough'}
                      ],
                    },
                    'text-shadow'
                ],
              },{
                name: 'Decorations',
                open: false,
                properties: [
                  'opacity',
                  'border-radius',
                  'border',
                  'box-shadow',
                  'background', // { id: 'background-bg', property: 'background', type: 'bg' }
                ],
              },{
                name: 'Extra',
                open: false,
                buildProps: [
                  'transition',
                  'perspective',
                  'transform'
                ],
              },{
                name: 'Flex',
                open: false,
                properties: [{
                  name: 'Flex Container',
                  property: 'display',
                  type: 'select',
                  defaults: 'block',
                  list: [
                    { value: 'block', name: 'Disable'},
                    { value: 'flex', name: 'Enable'}
                  ],
                },{
                  name: 'Flex Parent',
                  property: 'label-parent-flex',
                  type: 'integer',
                },{
                  name: 'Direction',
                  property: 'flex-direction',
                  type: 'radio',
                  defaults: 'row',
                  list: [{
                    value: 'row',
                    name: 'Row',
                    className: 'icons-flex icon-dir-row',
                    title: 'Row',
                  },{
                    value: 'row-reverse',
                    name: 'Row reverse',
                    className: 'icons-flex icon-dir-row-rev',
                    title: 'Row reverse',
                  },{
                    value: 'column',
                    name: 'Column',
                    title: 'Column',
                    className: 'icons-flex icon-dir-col',
                  },{
                    value: 'column-reverse',
                    name: 'Column reverse',
                    title: 'Column reverse',
                    className: 'icons-flex icon-dir-col-rev',
                  }],
                },{
                  name: 'Justify',
                  property: 'justify-content',
                  type: 'radio',
                  defaults: 'flex-start',
                  list: [{
                    value: 'flex-start',
                    className: 'icons-flex icon-just-start',
                    title: 'Start',
                  },{
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-just-end',
                  },{
                    value: 'space-between',
                    title: 'Space between',
                    className: 'icons-flex icon-just-sp-bet',
                  },{
                    value: 'space-around',
                    title: 'Space around',
                    className: 'icons-flex icon-just-sp-ar',
                  },{
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-just-sp-cent',
                  }],
                },{
                  name: 'Align',
                  property: 'align-items',
                  type: 'radio',
                  defaults: 'center',
                  list: [{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start',
                  },{
                    value: 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end',
                  },{
                    value: 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str',
                  },{
                    value: 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center',
                  }],
                },{
                  name: 'Flex Children',
                  property: 'label-parent-flex',
                  type: 'integer',
                },{
                  name: 'Order',
                  property: 'order',
                  type: 'integer',
                  defaults: 0,
                  min: 0
                },{
                  name: 'Flex',
                  property: 'flex',
                  type: 'composite',
                  properties  : [{
                    name: 'Grow',
                    property: 'flex-grow',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                  },{
                    name: 'Shrink',
                    property: 'flex-shrink',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                  },{
                    name: 'Basis',
                    property: 'flex-basis',
                    type: 'integer',
                    units: ['px','%',''],
                    unit: '',
                    defaults: 'auto',
                  }],
                },{
                  name: 'Align',
                  property: 'align-self',
                  type: 'radio',
                  defaults: 'auto',
                  list: [{
                    value: 'auto',
                    name: 'Auto',
                  },{
                    value: 'flex-start',
                    title: 'Start',
                    className: 'icons-flex icon-al-start',
                  },{
                    value   : 'flex-end',
                    title: 'End',
                    className: 'icons-flex icon-al-end',
                  },{
                    value   : 'stretch',
                    title: 'Stretch',
                    className: 'icons-flex icon-al-str',
                  },{
                    value   : 'center',
                    title: 'Center',
                    className: 'icons-flex icon-al-center',
                  }],
                }]
              }
            ],
          },
        },
      });

      editor.I18n.addMessages({
        en: {
          styleManager: {
            properties: {
              'background-repeat': 'Repeat',
              'background-position': 'Position',
              'background-attachment': 'Attachment',
              'background-size': 'Size',
            }
          },
        }
      });

      var pn = editor.Panels;
      var modal = editor.Modal;
      var cmdm = editor.Commands;
      cmdm.add('canvas-clear', function() {
        if(confirm('Are you sure to clean the canvas?')) {
          var comps = editor.DomComponents.clear();
          setTimeout(function(){ localStorage.clear()}, 0)
        }
      });
      cmdm.add('set-device-desktop', {
        run: function(ed) { ed.setDevice('Desktop') },
        stop: function() {},
      });
      cmdm.add('set-device-tablet', {
        run: function(ed) { ed.setDevice('Tablet') },
        stop: function() {},
      });
      cmdm.add('set-device-mobile', {
        run: function(ed) { ed.setDevice('Mobile portrait') },
        stop: function() {},
      });



      // Add info command
      var mdlClass = 'gjs-mdl-dialog-sm';
      var infoContainer = document.getElementById('info-panel');
      cmdm.add('open-info', function() {
        var mdlDialog = document.querySelector('.gjs-mdl-dialog');
        mdlDialog.className += ' ' + mdlClass;
        infoContainer.style.display = 'block';
        modal.setTitle('About this demo');
        modal.setContent(infoContainer);
        modal.open();
        modal.getModel().once('change:open', function() {
          mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
        })
      });
      pn.addButton('options', {
        id: 'open-info',
        className: 'fa fa-question-circle',
        command: function() { editor.runCommand('open-info') },
        attributes: {
          'title': 'About',
          'data-tooltip-pos': 'bottom',
        },
      });


      // Simple warn notifier
      var origWarn = console.warn;
      toastr.options = {
        closeButton: true,
        preventDuplicates: true,
        showDuration: 250,
        hideDuration: 150
      };
      console.warn = function (msg) {
        if (msg.indexOf('[undefined]') == -1) {
          toastr.warning(msg);
        }
        origWarn(msg);
      };


      // Add and beautify tooltips
      [['sw-visibility', 'Show Borders'], ['preview', 'Preview'], ['fullscreen', 'Fullscreen'],
       ['export-template', 'Export'], ['undo', 'Undo'], ['redo', 'Redo'],
       ['gjs-open-import-webpage', 'Import'], ['canvas-clear', 'Clear canvas']]
      .forEach(function(item) {
        pn.getButton('options', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
      });
      [['open-sm', 'Style Manager'], ['open-layers', 'Layers'], ['open-blocks', 'Blocks']]
      .forEach(function(item) {
        pn.getButton('views', item[0]).set('attributes', {title: item[1], 'data-tooltip-pos': 'bottom'});
      });
      var titles = document.querySelectorAll('*[title]');

      for (var i = 0; i < titles.length; i++) {
        var el = titles[i];
        var title = el.getAttribute('title');
        title = title ? title.trim(): '';
        if(!title)
          break;
        el.setAttribute('data-tooltip', title);
        el.setAttribute('title', '');
      }


      // Store and load events
      editor.on('storage:load', function(e) {  });
      editor.on('storage:store', function(e) { });
      var canvasDoc = editor.Canvas.getBody().ownerDocument;
      // Insert text at the current pointer position
      canvasDoc.execCommand("insertText", true, 'TEST');
      // Do stuff on load
      editor.on('load', function() {
        var $ = grapesjs.$;
        setTimeout(function(){ localStorage.clear()}, 0);
        htmlvalue= editor.getHtml();
        $('#html').val(htmlvalue);
        cssvalue= editor.getCss();
        $('#css').val(cssvalue);
        var full_html =htmlvalue+cssvalue;
        var html_data = $('#content').val(full_html);
        // Show borders by default
        pn.getButton('options', 'sw-visibility').set('active', 1);

        // Show logo with the version
        var logoCont = document.querySelector('.gjs-logo-cont');
        document.querySelector('.gjs-logo-version').innerHTML = 'v' + grapesjs.version;
        var logoPanel = document.querySelector('.gjs-pn-commands');
        logoPanel.appendChild(logoCont);


        // Load and show settings and style manager
        var openTmBtn = pn.getButton('views', 'open-tm');
        openTmBtn && openTmBtn.set('active', 1);
        var openSm = pn.getButton('views', 'open-sm');
        openSm && openSm.set('active', 1);

        // Add Settings Sector
        var traitsSector = $('<div class="gjs-sm-sector no-select">'+
          '<div class="gjs-sm-sector-title"><span class="icon-settings fa fa-cog"></span> <span class="gjs-sm-sector-label">Settings</span></div>' +
          '<div class="gjs-sm-properties" style="display: none;"></div></div>');
        var traitsProps = traitsSector.find('.gjs-sm-properties');
        traitsProps.append($('.gjs-trt-traits'));
        $('.gjs-sm-sectors').before(traitsSector);
        traitsSector.find('.gjs-sm-sector-title').on('click', function(){
          var traitStyle = traitsProps.get(0).style;
          var hidden = traitStyle.display == 'none';
          if (hidden) {
            traitStyle.display = 'block';
          } else {
            traitStyle.display = 'none';
          }
        });

        // Open block manager
        var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
        openBlocksBtn && openBlocksBtn.set('active', 1);

        // Move Ad

        const htmlTextarea = document.getElementById('content')
        //const cssTextarea = document.getElementById('css')
        const updateTextarea = (component, editor)=>{
          const e = component.em.get("Editor");
          htmlvalue= e.getHtml();
          cssvalue= e.getCss();
          htmlvalue= e.getHtml();
          $('#html').val(htmlvalue);
          cssvalue= e.getCss();
          $('#css').val(cssvalue);
          var full_html =htmlvalue+cssvalue;
          var html_data = $('#content').val(full_html);
        }
        editor.on('component:add', updateTextarea);
        editor.on('component:update', updateTextarea);
        editor.on('component:remove', updateTextarea);

      });
    </script>
<script type="text/javascript">
    // $('#actions').click(function(){
    //     //var htmlWithCss = editor.runCommand('gjs-get-inlined-html');
    //     var html_data = $('#content').val();
    //     // var landing_temp_name = $('#landing_name').val();
    //     // var button_text = $('#button_text').val();
    //     $.ajax(
    //         {
    //             url: "/editor_data",
    //             data: {html_data:html_data}, 
    //             success: function(result){
    //                     //console.log(result);return false;
                    
    //             }
    //         });
    //  });

    // $(window).on('keyup keypress', function(e) {
    //    if (e.type=="keyup") {
    //         var html = editor.getHtml();
    //         var css = editor.getCss();
    //         // var landing_temp_name = $('#name').val();
    //         // var button_text = $('#button_text').val();
    //         var html_data = "<style>"+css+"</style>"+html;
    //         $.ajax(
    //         {
    //             url: "/editor-data",
    //             data: {html_data:html_data}, 
    //             success: function(result){
    //             }
    //         });
    //    } 
    //    else {
    //         var html = editor.getHtml();
    //         var css = editor.getCss();
    //         // var landing_temp_name = $('#name').val();
    //         // var button_text = $('#button_text').val();
    //         var html_data =  "<style>"+css+"</style>"+html;
    //         $.ajax(
    //         {
    //             url: "/editor_data",
    //             data: {html_data:html_data}, 
    //             success: function(result){
    //             }
    //         }); 
    //    }
    // });
  
</script>   






