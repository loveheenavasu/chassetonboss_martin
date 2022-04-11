<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{__('Edit Gmail Connection')}}
            </h2>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:gmail-connection-form :gmailconnection="$gmailconnection" />
        </div>
        
    </div>
</x-app-layout>

