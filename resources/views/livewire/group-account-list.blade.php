<x-jet-action-section>
    <x-slot name="title">
        {{ __('Accounts in list') }}
    </x-slot>

    <x-slot name="description">
        Accounts belongs to this list.<br>
       
    </x-slot>

    <x-slot name="content">
        @if ($this->emails->isNotEmpty())
            <table id="emails-list" class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <th>Gmail Connections</th>
                    </tr>
                    @foreach ($this->emails as $email)
                        <tr>
                            <td class="text-xs px-1 py-1">
                                {{$email->email_id}}
                            </td> 
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($this->emails->hasPages())
                <div class="mt-4">
                    {{ $this->emails->fragment('emails-list')->links() }}
                </div>
            @endif
        @else
            <div>{{ __('No emails yet.') }}</div>
        @endif
    </x-slot>
</x-jet-action-section>


<style type="text/css">
    body .mt-5, .my-5 {
        margin-top: 0 !important;
    }
</style>

