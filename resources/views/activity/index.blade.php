<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activiteiten') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <x-link-button href="{{ route('activities.create') }}">
                        {{ __("Activiteit aanmaken") }}
                    </x-link-button>

                    <!-- Zoekbalk -->
                    <div class="mt-6 mb-4">
                        <form method="GET" action="{{ url()->current() }}" class="relative">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Zoek op titel, beschrijving of locatie..."
                                   class="w-full px-4 py-3 pl-11 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                   class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                            <!-- Hidden inputs to preserve sort parameters -->
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            @if(request('order'))
                                <input type="hidden" name="order" value="{{ request('order') }}">
                            @endif
                        </form>
                    </div>

                    <!-- Sorting Controls -->
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Sort Dropdown -->
                            <div class="relative">
                                <button id="sortDropdownButton" type="button"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                    </svg>
                                    <span id="sortButtonText">
                                        @if(request('sort') === 'start_time' && request('order') === 'asc')
                                            Datum (nieuw → oud)
                                        @elseif(request('sort') === 'start_time' && request('order') === 'desc')
                                            Datum (oud → nieuw)
                                        @elseif(request('sort') === 'name' && request('order') === 'asc')
                                            Naam (A → Z)
                                        @elseif(request('sort') === 'name' && request('order') === 'desc')
                                            Naam (Z → A)
                                        @elseif(request('sort') === 'participants' && request('order') === 'asc')
                                            Deelnemers (weinig → veel)
                                        @elseif(request('sort') === 'participants' && request('order') === 'desc')
                                            Deelnemers (veel → weinig)
                                        @else
                                            Sorteer op
                                        @endif
                                    </span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div id="sortDropdownMenu" class="hidden absolute left-0 mt-1 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                                    <div class="py-1">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'asc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'start_time' && request('order') === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Datum (nieuw → oud)
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'desc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'start_time' && request('order') === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Datum (oud → nieuw)
                                        </a>
                                        <hr class="my-1">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'name' && request('order') === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Naam (A → Z)
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'desc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'name' && request('order') === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Naam (Z → A)
                                        </a>
                                        <hr class="my-1">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'participants', 'order' => 'asc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'participants' && request('order') === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Deelnemers (weinig → veel)
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'participants', 'order' => 'desc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'participants' && request('order') === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Deelnemers (veel → weinig)
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Results count -->
                            <span class="text-sm text-gray-600">
                                {{ $activities->total() }} activiteit{{ $activities->total() !== 1 ? 'en' : '' }}
                                @if(request('search'))
                                    <span class="text-gray-500">voor "{{ request('search') }}"</span>
                                @endif
                            </span>
                        </div>

                        <!-- Reset button -->
                        @if(request()->has('sort') || request()->has('order') || request()->has('search'))
                            <a href="{{ url()->current() }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Reset filters
                            </a>
                        @endif
                    </div>

                    <div class="mt-6">
                        @if($activities->count())
                            <ul class="space-y-3">
                                @foreach($activities as $activity)
                                    @php
                                        $now = now();
                                        $isPast = $activity->end_time < $now;
                                        $isOngoing = $activity->start_time <= $now && $activity->end_time >= $now;
                                        $isUpcoming = $activity->start_time > $now;
                                        $totalParticipants = $activity->users->count() + $activity->externals->count();
                                    @endphp
                                    <li>
                                        <a href="{{ route('activities.show', $activity) }}"
                                           class="block px-6 py-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 transition duration-200 ease-in-out shadow-sm hover:shadow-md {{ $isPast ? 'opacity-75' : '' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-base font-semibold text-gray-900">
                                                            {{ $activity->name }}
                                                        </span>

                                                        @if($isPast)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Afgelopen
                                                            </span>
                                                        @elseif($isOngoing)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                <svg class="w-3 h-3 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Bezig
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Aankomend
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            {{ $activity->start_time->format('d-m-Y H:i') }}
                                                        </span>

                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                            </svg>
                                                            {{ $totalParticipants }} deelnemer{{ $totalParticipants != 1 ? 's' : '' }}
                                                        </span>

                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            {{ $activity->location }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-6">
                                {{ $activities->links() }}
                            </div>
                        @else
                            <div class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                                @if(request('search'))
                                    Geen activiteiten gevonden voor "{{ request('search') }}".
                                @else
                                    Er zijn nog geen activiteiten.
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortButton = document.getElementById('sortDropdownButton');
            const sortMenu = document.getElementById('sortDropdownMenu');

            if (sortButton && sortMenu) {
                sortButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sortMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!sortButton.contains(e.target) && !sortMenu.contains(e.target)) {
                        sortMenu.classList.add('hidden');
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const sortMenu = document.getElementById('sortDropdownMenu');
                    if (sortMenu && !sortMenu.classList.contains('hidden')) {
                        sortMenu.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</x-app-layout>
