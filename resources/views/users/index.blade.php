<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gebruikers
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <x-link-button href="{{ route('users.create') }}">
                        Nieuwe Gebruiker
                    </x-link-button>

                    <!-- 🔍 Zoekbalk -->
                    <div class="mt-6 mb-4">
                        <form method="GET" action="{{ url()->current() }}" class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="zoek een gebruiker..."
                                   class="w-full px-4 py-3 pl-11 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            @if(request('search'))
                                <a href="{{ url()->current() }}" 
                                   class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                        Voornaam
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                        Achternaam
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                        E-mail
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                        Functietitel
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-gray-50">
                                @forelse($users as $user)
                                    <tr 
                                        onclick="window.location='{{ route('users.show', $user->id) }}'"
                                        class="cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition duration-200 ease-in-out border border-transparent"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $user->first_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($user->job_title)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $user->job_title }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Geen functie</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center bg-gray-50 rounded-lg">
                                            @if(request('search'))
                                                Geen gebruikers gevonden voor "{{ request('search') }}".
                                            @else
                                                Er zijn nog geen gebruikers.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
