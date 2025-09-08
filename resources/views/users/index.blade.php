<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <x-link-button href="{{ route('users.create') }}">
                        + New User
                    </x-link-button>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    First name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    Last name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    E-mail
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                    Job title
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->first_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $car->job_title }}
                                    </td>
                                    {{--                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">--}}
                                    {{--                                        <x-link-button href="{{ route('students.show', $student->id) }}">--}}
                                    {{--                                            Show more--}}
                                    {{--                                        </x-link-button>--}}
                                    {{--                                    </td>--}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
