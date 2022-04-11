<div>

        <x-slot name="title">
            {{ __('Manage Groups List') }}
        </x-slot>

        <div class="mt-5 md:mt-0">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="space-y-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Name') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Accounts') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                               
                            </tbody>
                        </table>
                        
                </div>
            </div>
        </div>

</div>