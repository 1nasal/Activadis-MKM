<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gebruikersdetails
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-8 space-y-8">
                    
                    {{-- Header --}}
                    <div class="flex items-center justify-between border-b pb-4">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h3>
                            <p class="text-sm text-gray-500">Gebruikersinformatie</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($user->job_title)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $user->job_title }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Gebruikersinformatie --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="block text-sm text-gray-500">ID</span>
                            <span class="font-medium">{{ $user->id }}</span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="block text-sm text-gray-500">Voornaam</span>
                            <span class="font-medium">{{ $user->first_name }}</span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="block text-sm text-gray-500">Achternaam</span>
                            <span class="font-medium">{{ $user->last_name }}</span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="block text-sm text-gray-500">E-mailadres</span>
                            <span class="font-medium">{{ $user->email }}</span>
                        </div>
                    </div>

                    {{-- Actieknoppen --}}
                    <div class="flex flex-wrap gap-3 pt-4 border-t">
                        <x-link-button href="{{ route('users.index') }}">
                            ← Terug naar lijst
                        </x-link-button>

                        <a href="{{ route('users.edit', $user->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-yellow-400 text-black font-semibold rounded-md hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition">
                            Gebruiker bewerken
                        </a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Weet u zeker dat u deze gebruiker wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                Gebruiker verwijderen
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
