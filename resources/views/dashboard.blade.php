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
                    <!-- Zoekbalk -->
                    <div class="mb-4">
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
                                {{ $activities->count() }} activiteit{{ $activities->count() !== 1 ? 'en' : '' }}
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
                                        <div class="block px-6 py-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 cursor-pointer" onclick="openActivityModal({{ $activity->id }})">
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
                                                                €{{ number_format($activity->cost, 2, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <form action="{{ route('activities.leave', $activity) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je je wilt uitschrijven voor deze activiteit?');" class="ml-4" onclick="event.stopPropagation();">
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
                                @if(request('search'))
                                    Geen aankomende activiteiten gevonden voor "{{ request('search') }}".
                                @else
                                    Je bent nog niet ingeschreven voor aankomende activiteiten.
                                @endif
                            </div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Activity Details Modal (ONLY THIS PART WAS CHANGED) -->
    <div id="activityModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-start justify-center p-4 pt-20">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[calc(100vh-6rem)] overflow-y-auto transform transition-all">
            <div class="sticky top-0 bg-white/80 backdrop-blur border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 id="modalActivityTitle" class="text-xl font-semibold text-gray-900"></h3>
                <button onclick="closeActivityModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div id="modalContent"></div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button id="modalLeaveButton" onclick="leaveActivityFromModal()" type="button"
                            class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition inline-flex items-center">
                        Uitschrijven
                    </button>
                    <button onclick="closeActivityModal()" type="button"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Sluiten
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
        let __modalCarousel = { index: 0, total: 0, touchStartX: null };

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

        // OPEN MODAL (redesigned content but ONLY used here)
        window.openActivityModal = function(activityId) {
            const a = activitiesData[activityId];
            if (!a) return;

            selectedActivityId = activityId;
            document.getElementById('modalActivityTitle').textContent = a.name;

            let html = '';

            // Images
            html += '<div class="mb-6">';
            if (a.images && a.images.length > 0) {
                if (a.images.length === 1) {
                    html += `
                        <div class="w-full h-64 md:h-80 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center ring-1 ring-gray-100">
                            <img src="${a.images[0].url}" alt="${a.images[0].name ?? a.name}" class="max-h-full max-w-full object-contain">
                        </div>`;
                } else {
                    html += `
                        <div id="modal-carousel" class="relative rounded-xl overflow-hidden bg-gray-50 ring-1 ring-gray-100">
                            <div id="modal-carousel-track" class="relative w-full h-64 md:h-80">
                                ${a.images.map((img, i) => `
                                    <img src="${img.url}" alt="${img.name ?? a.name}"
                                         class="absolute inset-0 w-full h-full object-contain transition-opacity duration-300 ${i===0?'opacity-100':'opacity-0'}"
                                         data-slide="${i}">
                                `).join('')}
                            </div>
                            <button type="button" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center"
                                    onclick="modalCarouselPrev()" aria-label="Vorige">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center"
                                    onclick="modalCarouselNext()" aria-label="Volgende">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
                            </button>
                            <div id="modal-carousel-dots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                                ${a.images.map((_, i) => `
                                    <button type="button" class="w-2.5 h-2.5 rounded-full ${i===0?'bg-white':'bg-white/50'} ring-1 ring-white/50 hover:bg-white"
                                            onclick="modalCarouselGoTo(${i})" aria-label="Ga naar afbeelding ${i+1}"></button>
                                `).join('')}
                            </div>
                        </div>`;
                }
            } else if (a.primary_image) {
                html += `
                    <div class="w-full h-64 md:h-80 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center ring-1 ring-gray-100">
                        <img src="${a.primary_image}" alt="${a.name}" class="max-h-full max-w-full object-contain">
                    </div>`;
            }
            html += '</div>';

            // Badges
            html += `
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="inline-flex items-center rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-700">Locatie: ${a.location}</span>
                    <span class="inline-flex items-center rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-700">Start: ${a.start_time}</span>
                    ${a.end_time ? `<span class="inline-flex items-center rounded-full border border-gray-200 px-3 py-1 text-xs text-gray-700">Einde: ${a.end_time}</span>` : ''}
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs ${a.cost > 0 ? 'border border-blue-200 text-blue-700' : 'border border-green-200 text-green-700'}">
                        ${a.cost > 0 ? ('€' + Number(a.cost).toFixed(2).replace('.', ',')) : 'Gratis'}
                    </span>
                    ${a.includes_food ? `<span class="inline-flex items-center rounded-full border border-emerald-200 text-emerald-700 px-3 py-1 text-xs">Eten inbegrepen</span>` : ''}
                </div>
            `;

            // Info grid
            html += `
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <div class="rounded-xl border border-gray-100 p-4">
                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Capaciteit</div>
                            <div class="text-gray-800">
                                Huidig: <span class="font-medium">${a.total_participants ?? 0}</span>
                                ${a.max_participants ? `/ <span class="font-medium">${a.max_participants}</span>` : ''}
                            </div>
                            ${a.min_participants ? `<div class="text-gray-700">Minimaal: <span class="font-medium">${a.min_participants}</span></div>` : ''}
                        </div>
                        ${a.requirements ? `
                        <div class="rounded-xl border border-gray-100 p-4">
                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Vereisten</div>
                            <div class="prose prose-sm max-w-none text-gray-700">${a.requirements}</div>
                        </div>` : ''}
                    </div>
                    <div class="rounded-xl border border-gray-100 p-4 h-full">
                        <div class="text-xs uppercase tracking-wide text-gray-500 mb-2">Beschrijving</div>
                        <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed">
                            ${a.description ? a.description : 'Geen beschrijving beschikbaar.'}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = html;

            const leaveBtn = document.getElementById('modalLeaveButton');
            if (a.is_past) {
                leaveBtn.classList.add('hidden');
                leaveBtn.disabled = true;
            } else {
                leaveBtn.classList.remove('hidden');
                leaveBtn.disabled = false;
            }

            if (a.images && a.images.length > 1) initModalCarousel(a.images.length);

            document.getElementById('activityModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        // Close modal
        window.closeActivityModal = function() {
            document.getElementById('activityModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.removeEventListener('keydown', modalCarouselKeyHandler);
            selectedActivityId = null;
        };

        // Leave action from modal
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

        // Image lightbox (used by modal grid when needed)
        window.openImageModal = function(imageUrl, imageName) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImage').alt = imageName || '';
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };
        window.closeImageModal = function() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        // Modal carousel helpers
        function initModalCarousel(total) {
            __modalCarousel.index = 0;
            __modalCarousel.total = total;
            document.addEventListener('keydown', modalCarouselKeyHandler);

            const track = document.getElementById('modal-carousel-track');
            if (track) {
                track.addEventListener('touchstart', (e) => {
                    __modalCarousel.touchStartX = e.touches[0].clientX;
                }, { passive: true });

                track.addEventListener('touchend', (e) => {
                    if (__modalCarousel.touchStartX === null) return;
                    const delta = e.changedTouches[0].clientX - __modalCarousel.touchStartX;
                    __modalCarousel.touchStartX = null;
                    const threshold = 30;
                    if (delta > threshold) modalCarouselPrev();
                    if (delta < -threshold) modalCarouselNext();
                }, { passive: true });
            }
        }
        function modalCarouselKeyHandler(e) {
            const modal = document.getElementById('activityModal');
            if (!modal || modal.classList.contains('hidden')) return;
            if (e.key === 'ArrowRight') modalCarouselNext();
            if (e.key === 'ArrowLeft') modalCarouselPrev();
        }
        function modalCarouselGoTo(i) {
            if (__modalCarousel.total <= 0) return;
            __modalCarousel.index = (i + __modalCarousel.total) % __modalCarousel.total;
            renderModalCarousel();
        }
        function modalCarouselNext() {
            if (__modalCarousel.total <= 0) return;
            __modalCarousel.index = (__modalCarousel.index + 1) % __modalCarousel.total;
            renderModalCarousel();
        }
        function modalCarouselPrev() {
            if (__modalCarousel.total <= 0) return;
            __modalCarousel.index = (__modalCarousel.index - 1 + __modalCarousel.total) % __modalCarousel.total;
            renderModalCarousel();
        }
        function renderModalCarousel() {
            const slides = document.querySelectorAll('#modal-carousel-track [data-slide]');
            const dots   = document.querySelectorAll('#modal-carousel-dots > button');
            slides.forEach((el, i) => {
                el.classList.toggle('opacity-100', i === __modalCarousel.index);
                el.classList.toggle('opacity-0',   i !== __modalCarousel.index);
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-white', i === __modalCarousel.index);
                dot.classList.toggle('bg-white/50', i !== __modalCarousel.index);
            });
        }

        // Misc events (unchanged)
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