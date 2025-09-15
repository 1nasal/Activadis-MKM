<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User Details
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">User Info</h3>
                        <p class="text-sm text-gray-600">User info.</p>
                    </div>

                    <div class="flex flex-col space-y-4">
                        <div>
                            <span class="font-semibold">ID:</span> {{ $user->id }}
                        </div>
                        <div>
                            <span class="font-semibold">Firstname:</span> {{ $user->first_name }}
                        </div>
                        <div>
                            <span class="font-semibold">Lastname:</span> {{ $user->last_name }}
                        </div>
                        <div>
                            <span class="font-semibold">E-mail:</span> {{ $user->email }}
                        </div>
                        <div>
                            <span class="font-semibold">Password:</span> {{ $user->password }}
                        </div>
                        <div>
                            <span class="font-semibold">Job title:</span> {{ $user->job_title }}
                        </div>
                        <div>
                            <span class="font-semibold">Role:</span> {{ $user->role }}
                        </div>
                    </div>

                    <div class="flex flex-col space-y-2">

                        <x-link-button href="{{ route('users.index') }}" style="width: 150px;">
                            ← Back to list
                        </x-link-button>

                        <a href="{{ route('users.edit', $user->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-yellow-400 text-black font-semibold rounded-md hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition" style="width: 150px;">
                            Edit User
                        </a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                Delete User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
