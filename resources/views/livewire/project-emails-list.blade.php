<x-jet-action-section>
    <x-slot name="title">
        {{ __('Project assigned Emails list') }}
    </x-slot>

    <x-slot name="description">
        Emails belongs to this list.<br>
        Total: <strong>{{ $projectlist->projectemails()->count() }}</strong><br>
    </x-slot>
    <x-slot name="content">
        @if ($this->projectemails->isNotEmpty())
            <table id="emails-list" class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($this->projectemails as $email)
                        <tr>
                            <td class="text-xs px-1 py-1">{{ $email->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div>{{ __('No emails yet.') }}</div>
        @endif
    </x-slot>
</x-jet-action-section>
