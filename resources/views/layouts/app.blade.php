<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @livewireStyles

        @stack('styles')

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:navigation />

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <script src="{{ asset('js/app.js') }}"></script>

        @stack('scripts')

        @if((session()->has('copyToClipboard')))
            <script>
                copyTextToClipboard(@json(session('copyToClipboard')['value'].'/'))

                setTimeout(() => document.getElementById('copied-notification').classList.add('hidden'), 3000)
            </script>

            <div id="copied-notification" class="fixed right-0 bottom-0 m-5">
                <!-- Toast Notification Info -->
                <div class="flex items-center bg-blue-400 border-l-4 border-blue-700 py-2 px-3 shadow-md mb-2 rounded-lg">
                    <!-- icons -->
                    <div class="text-blue-500 rounded-full bg-white mr-3">
                        <svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-info" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                            <circle cx="8" cy="4.5" r="1"/>
                        </svg>
                    </div>
                    <!-- message -->
                    <div class="text-white max-w-xs ">
                        {{ session('copyToClipboard')['text'] ?? 'Result copied to clipboard' }}
                    </div>
                </div>
            </div>
        @endif
        <div style="margin-top: 15px;margin-bottom: 20px;">
            <div>
                <footer class="main-footer">
                   <p>
                        <a href="https://referer.commissionbanjar.com/privacy-policy" style="text-decoration: underline;margin-left: 600px;margin-right: 20px;color: #6b7280;font-size: 14px;font-weight: 600;">Privacy Policy</a>
                        <a href="https://referer.commissionbanjar.com/terms-conditions" style="text-decoration: underline;margin-left:0px;margin-right: 20px;color: #6b7280;font-size: 14px;font-weight: 600;">Terms and Conditions
                        </a>
                    </p>
                  
                </footer>
            </div>
        </div>
    </body>
</html>
