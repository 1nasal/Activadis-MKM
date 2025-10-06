<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mijn Activiteiten') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
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
                                            Datum (vroeg → laat)
                                        @elseif(request('sort') === 'start_time' && request('order') === 'desc')
                                            Datum (laat → vroeg)
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
                                            Datum (vroeg → laat)
                                        </a>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'desc']) }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('sort') === 'start_time' && request('order') === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                            Datum (laat → vroeg)
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
                            <span class="text-sm text-gray-600">{{ $activities->count() }} activiteiten</span>
                        </div>

                        <!-- Reset button -->
                        @if(request()->has('sort') || request()->has('order'))
                            <a href="{{ url()->current() }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                                Reset
                            </a>
                        @endif
                    </div>

                    <!-- Toekomstige activiteiten -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aankomende Activiteiten</h3>
                        
                        @php
                            $upcomingActivities = $activities->filter(fn($activity) => $activity->end_time >= now());
                        @endphp

                        @if($upcomingActivities->count())
                            <ul class="space-y-3">
                                @foreach($upcomingActivities as $activity)
                                    <li>
                                        <div class="block px-6 py-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm cursor-pointer hover:border-blue-300 transition-colors" onclick="openActivityModal({{ $activity->id }})">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-3">
                                                        <span class="text-base font-semibold text-gray-900">
                                                            {{ $activity->name }}
                                                        </span>
                                                        
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Ingeschreven
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            {{ $activity->start_time->format('d-m-Y H:i') }}
                                                        </span>
                                                        
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            {{ $activity->location }}
                                                        </span>

                                                        @if($activity->cost > 0)
                                                            <span class="flex items-center">
                                                             
                                                                € {{ number_format($activity->cost, 2, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <form action="{{ route('activities.leave', $activity) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je je wilt uitschrijven voor deze activiteit?');" class="ml-4">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Uitschrijven
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                                Je bent nog niet ingeschreven voor aankomende activiteiten.
                            </div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Activity Details Modal -->
    <div id="activityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-start justify-center p-4 pt-20">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[calc(100vh-6rem)] overflow-y-auto transform transition-all">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h3 id="modalActivityTitle" class="text-xl font-semibold text-gray-900"></h3>
                <button onclick="closeActivityModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div id="modalContent"></div>

                <div class="mt-6 flex gap-4">
                    <button id="modalLeaveButton" onclick="leaveActivityFromModal()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Uitschrijven
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-100 z-10">
                ×
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        let selectedActivityId = null;
        let activitiesData = {};

        @foreach($activities as $activity)
        activitiesData[{{ $activity->id }}] = {
            id: {{ $activity->id }},
            name: @json($activity->name),
            description: @json($activity->description),
            requirements: @json($activity->requirements),
            location: @json($activity->location),
            start_time: @json($activity->start_time->format('d-m-Y H:i')),
            end_time: @json($activity->end_time ? $activity->end_time->format('d-m-Y H:i') : null),
            cost: {{ $activity->cost }},
            includes_food: {{ $activity->includes_food ? 'true' : 'false' }},
            max_participants: {{ $activity->max_participants ?? 'null' }},
            min_participants: {{ $activity->min_participants ?? 'null' }},
            images: [
                @foreach($activity->images as $image)
                { url: @json($image->url), name: @json($image->original_name) },
                @endforeach
            ],
            primary_image: @json($activity->primary_image),
            total_participants: {{ $activity->users->count() + $activity->externals->count() }},
            is_past: {{ $activity->end_time < now() ? 'true' : 'false' }}
        };
        @endforeach

        window.openActivityModal = function(activityId) {
            const activity = activitiesData[activityId];
            if (!activity) return;

            selectedActivityId = activityId;
            document.getElementById('modalActivityTitle').textContent = activity.name;

            let modalContent = '';

            if (activity.images.length > 0 || activity.primary_image) {
                modalContent += '<div class="mb-6">';
                if (activity.images.length > 0) {
                    if (activity.images.length === 1) {
                        modalContent += `<img src="${activity.images[0].url}" alt="${activity.name}" class="w-full h-64 object-cover rounded-lg">`;
                    } else {
                        modalContent += '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
                        activity.images.forEach(image => {
                            modalContent += `<div class="group cursor-pointer" onclick="openImageModal('${image.url}', '${image.name}')">
                                <img src="${image.url}" alt="${image.name}" class="w-full h-48 object-cover rounded-lg group-hover:opacity-90 transition-opacity">
                            </div>`;
                        });
                        modalContent += '</div>';
                    }
                } else if (activity.primary_image) {
                    modalContent += `<img src="${activity.primary_image}" alt="${activity.name}" class="w-full h-64 object-cover rounded-lg">`;
                }
                modalContent += '</div>';
            }

            modalContent += '<div class="grid md:grid-cols-2 gap-8 mb-8">';
            modalContent += '<div class="space-y-4">';
            modalContent += `<div><strong class="text-gray-700">Locatie:</strong> <span class="ml-2">${activity.location}</span></div>`;
            modalContent += `<div><strong class="text-gray-700">Starttijd:</strong> <span class="ml-2">${activity.start_time}</span></div>`;
            if (activity.end_time) {
                modalContent += `<div><strong class="text-gray-700">Eindtijd:</strong> <span class="ml-2">${activity.end_time}</span></div>`;
            }
            modalContent += `<div><strong class="text-gray-700">Kosten:</strong> <span class="ml-2">${activity.cost > 0 ? '€' + activity.cost.toFixed(2).replace('.', ',') : 'Gratis'}</span></div>`;
            modalContent += '</div>';

            modalContent += '<div class="space-y-4">';
            if (activity.includes_food) {
                modalContent += `<div>Eten inbegrepen</div>`;
            }
            if (activity.max_participants) {
                modalContent += `<div><strong class="text-gray-700">Maximaal aantal deelnemers:</strong> <span class="ml-2">${activity.max_participants}</span></div>`;
            }
            if (activity.min_participants) {
                modalContent += `<div><strong class="text-gray-700">Minimaal aantal deelnemers:</strong> <span class="ml-2">${activity.min_participants}</span></div>`;
            }
            modalContent += `<div><strong class="text-gray-700">Huidige deelnemers:</strong> <span class="ml-2">${activity.total_participants}</span></div>`;
            modalContent += '</div></div>';

            if (activity.description) {
                modalContent += `<div class="mb-8"><h4 class="text-xl font-semibold mb-3">Beschrijving</h4><p class="text-gray-700 leading-relaxed">${activity.description}</p></div>`;
            }
            if (activity.requirements) {
                modalContent += `<div class="mb-8"><h4 class="text-xl font-semibold mb-3">Vereisten</h4><p class="text-gray-700 leading-relaxed">${activity.requirements}</p></div>`;
            }

            document.getElementById('modalContent').innerHTML = modalContent;

            const leaveButton = document.getElementById('modalLeaveButton');
            if (activity.is_past) {
                leaveButton.style.display = 'none';
            } else {
                leaveButton.style.display = 'block';
            }

            document.getElementById('activityModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeActivityModal = function() {
            document.getElementById('activityModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            selectedActivityId = null;
        };

        window.leaveActivityFromModal = function() {
            if (!selectedActivityId) return;
            if (!confirm('Weet je zeker dat je je wilt uitschrijven voor deze activiteit?')) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/activities/${selectedActivityId}/leave`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        };

        window.openImageModal = function(imageUrl, imageName) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImage').alt = imageName;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeImageModal = function() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

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

            const activityModal = document.getElementById('activityModal');
            const imageModal = document.getElementById('imageModal');

            if (activityModal) {
                activityModal.addEventListener('click', function(e) {
                    if (e.target === this) closeActivityModal();
                });
            }

            if (imageModal) {
                imageModal.addEventListener('click', function(e) {
                    if (e.target === this) closeImageModal();
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (activityModal && !activityModal.classList.contains('hidden')) {
                        closeActivityModal();
                    } else if (imageModal && !imageModal.classList.contains('hidden')) {
                        closeImageModal();
                    }
                }
            });
        });
    </script>
</x-app-layout>