<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bewerk Gebruiker
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Voornaam</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('first_name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Achternaam</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('last_name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">E-mail</label>
                            <input type="text" name="email" value="{{ old('email', $user->email) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Functietitel</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('job_title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Rol</label>
                            <input type="text" name="role" value="{{ old('role', $user->role) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('role') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <x-link-button href="{{ route('users.index') }}">
                                Annuleren
                            </x-link-button>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-400 text-black font-semibold rounded-md hover:bg-yellow-500">
                                Wijzigingen Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
