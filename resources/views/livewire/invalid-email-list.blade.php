<div>
    <div class="md:grid md:gap-6">
        <x-jet-section-title>
                <x-slot name="title">{{ __('All List') }}</x-slot>
                <x-slot name="description">List of Invalid email.</x-slot>
        </x-jet-section-title>

        <div class="mt-5 md:mt-0">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="space-y-6">
                	@if ($this->invalidemails->isNotEmpty())
                	<table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('ID') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Email') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Type') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Timezone') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Rule Number') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Rule Name') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Created At') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($this->invalidemails as $invalidemail)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->id }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->email }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->status }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->type }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->timezone }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->rule_number}}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->rule_name }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            {{ $invalidemail->created_at }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($this->invalidemails->hasPages())
                            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                                {{ $this->invalidemails->links() }}
                            </div>
                        @endif
                        @else
                        <div class="px-4 py-3 sm:px-6">
                            {{ __('No Invalid Email yet.') }}
                        </div>
                	@endif
                </div>
            </div>
        </div>
    </div>
</div>
