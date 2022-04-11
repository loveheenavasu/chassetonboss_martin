<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{__('Edit Event')}}        {{ $event->event_name }}
            </h2>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:event-calender-form :event="$event" />
        </div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:eventrule-monitoring :event="$event" />
        </div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:eventrule-action-history :event="$event" />
        </div>
    </div>
</x-app-layout>

