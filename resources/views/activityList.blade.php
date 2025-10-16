<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <header>
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Aankomende Activiteiten</h1>
        </header>

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

        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <button id="sortDropdownButton" type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                        <span id="sortButtonText">
                            @if($sortBy === 'start_time' && $sortOrder === 'asc')
                                Datum (vroeg → laat)
                            @elseif($sortBy === 'start_time' && $sortOrder === 'desc')
                                Datum (laat → vroeg)
                            @elseif($sortBy === 'name' && $sortOrder === 'asc')
                                Naam (A → Z)
                            @elseif($sortBy === 'name' && $sortOrder === 'desc')
                                Naam (Z → A)
                            @elseif($sortBy === 'participants' && $sortOrder === 'asc')
                                Deelnemers (weinig → veel)
                            @elseif($sortBy === 'participants' && $sortOrder === 'desc')
                                Deelnemers (veel → weinig)
                            @else
                                Sorteer op
                            @endif
                        </span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="sortDropdownMenu"
                         class="hidden absolute left-0 mt-1 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                        <div class="py-1">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'asc']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'start_time' && $sortOrder === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Datum (vroeg → laat)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'desc']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'start_time' && $sortOrder === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Datum (laat → vroeg)
                            </a>
                            <hr class="my-1">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'name' && $sortOrder === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Naam (A → Z)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'desc']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'name' && $sortOrder === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Naam (Z → A)
                            </a>
                            <hr class="my-1">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'participants', 'order' => 'asc']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'participants' && $sortOrder === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Deelnemers (weinig → veel)
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'participants', 'order' => 'desc']) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'participants' && $sortOrder === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">
                                Deelnemers (veel → weinig)
                            </a>
                        </div>
                    </div>
                </div>

                <span class="text-sm text-gray-600">
                    {{ $activities->total() }} activiteit{{ $activities->total() !== 1 ? 'en' : '' }}
                    @if(request('search'))
                        <span class="text-gray-500">voor "{{ request('search') }}"</span>
                    @endif
                </span>
            </div>

            @if(request()->has('sort') || request()->has('order') || request()->has('search'))
                <a href="{{ url()->current() }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                    Reset filters
                </a>
            @endif
        </div>

        @if($activities->count() > 0)
            <div class="space-y-4">
                @foreach($activities as $activity)
                    @php
                        $confirmedExternalsCount = $activity->externals()
                            ->wherePivot('confirmed', true)
                            ->count();

                        $totalParticipants = $activity->users->count() + $confirmedExternalsCount;

                        $isFull = $activity->max_participants && $totalParticipants >= $activity->max_participants;
                        $isEnrolled = auth()->check() && $activity->users->contains(auth()->id());
                    @endphp

                    <article
                        class="bg-white border border-gray-200 p-6 hover:border-gray-300 transition-colors cursor-pointer"
                        onclick="openActivityModal({{ $activity->id }})">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="md:w-64 flex-shrink-0 relative">
                                @if($activity->images->count() > 1)
                                    <div class="relative group">
                                        <div class="image-carousel" id="carousel-{{ $activity->id }}">
                                            @foreach($activity->images as $index => $image)
                                                <img src="{{ $image->url }}"
                                                     alt="{{ $image->original_name }}"
                                                     class="w-full h-48 object-cover rounded-lg border carousel-image {{ $index === 0 ? '' : 'hidden' }}"
                                                     data-index="{{ $index }}">
                                            @endforeach
                                        </div>

                                        <button onclick="event.stopPropagation(); previousImage({{ $activity->id }})"
                                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/70">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                        <button onclick="event.stopPropagation(); nextImage({{ $activity->id }})"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/70">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>

                                        <div
                                            class="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span id="counter-{{ $activity->id }}">1</span>/{{ $activity->images->count() }}
                                        </div>
                                    </div>
                                @elseif($activity->images->count() === 1)
                                    <img src="{{ $activity->images->first()->url }}"
                                         alt="{{ $activity->images->first()->original_name }}"
                                         class="w-full h-48 object-cover rounded-lg border">
                                @else
                                    <img src="{{ $activity->primary_image_url }}"
                                         alt="{{ $activity->name }}"
                                         class="w-full h-48 object-cover rounded-lg border">
                                @endif
                            </div>

                            <div class="md:w-40 flex-shrink-0">
                                <div class="text-sm text-gray-500 mb-1">
                                    {{ $activity->start_time->format('j M') }}
                                </div>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $activity->start_time->format('H:i') }}
                                </div>
                                @if($activity->end_time)
                                    <div class="text-sm text-gray-600 mt-1">
                                        tot {{ $activity->end_time->format('H:i') }}
                                    </div>
                                @endif
                                @if($activity->cost > 0)
                                    <div class="text-sm font-medium text-blue-600 mt-2">
                                        €{{ number_format($activity->cost, 2) }}
                                    </div>
                                @else
                                    <div class="text-sm font-medium text-green-600 mt-2">Gratis</div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">
                                    {{ $activity->name }}
                                </h3>

                                <div class="text-gray-600 text-sm space-y-1 mb-3">
                                    <div>Locatie: {{ $activity->location }}</div>

                                    <div>{{ $totalParticipants }} deelnemers
                                        @if($activity->max_participants)
                                            van {{ $activity->max_participants }}
                                        @endif
                                    </div>

                                    @if($activity->includes_food)
                                        <div>Eten inbegrepen</div>
                                    @endif
                                </div>

                                <p class="text-gray-700 text-sm leading-relaxed mb-4">
                                    @php
                                        $maxLength = 150;
                                        $description = $activity->description;
                                        if (strlen($description) > $maxLength) {
                                            $description = substr($description, 0, $maxLength);
                                            $description = substr($description, 0, strrpos($description, ' ')) . '...';
                                        }
                                    @endphp
                                    {{ $description }}
                                </p>
                            </div>

                            <div class="md:w-32 flex-shrink-0 flex md:flex-col gap-2">
                                @if($isEnrolled)
                                    <button
                                        onclick="event.stopPropagation(); openActivityModal({{ $activity->id }})"
                                        class="px-4 py-2 text-sm font-medium border border-green-600 text-green-600 hover:bg-green-50 transition-colors">
                                        Ingeschreven
                                    </button>
                                @else
                                    <button
                                        id="join-btn-{{ $activity->id }}"
                                        onclick="event.stopPropagation(); @auth joinActivityDirectly({{ $activity->id }}) @else openParticipantModal({{ $activity->id }}) @endauth"
                                        class="px-4 py-2 text-sm font-medium border transition-colors {{ $isFull ? 'border-gray-300 text-gray-500 cursor-not-allowed' : 'border-blue-600 text-blue-600 hover:bg-blue-50' }}"
                                        {{ $isFull ? 'disabled' : '' }}>
                                        <span class="join-btn-text">{{ $isFull ? 'Vol' : 'Inschrijven' }}</span>
                                        <svg class="join-btn-spinner hidden animate-spin h-4 w-4 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $activities->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Geen activiteiten gevonden</h3>
                <p class="text-gray-600">
                    @if(request('search'))
                        Er zijn geen activiteiten die voldoen aan de zoekopdracht "{{ request('search') }}".
                    @else
                        Er zijn momenteel geen aankomende activiteiten met de huidige filters.
                    @endif
                </p>
                @if(request()->has('sort') || request()->has('order') || request()->has('search'))
                    <a href="{{ url()->current() }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 underline">
                        Reset filters om alle activiteiten te bekijken
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Activity Details Modal -->
    <div id="activityModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-start justify-center p-4 pt-20">
        <div
            class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[calc(100vh-6rem)] overflow-y-auto transform transition-all">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h3 id="modalActivityTitle" class="text-xl font-semibold text-gray-900"></h3>
                <button onclick="closeActivityModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div id="modalContent"></div>

                <div class="mt-6 flex gap-4">
                    <button
                        id="modalJoinButton"
                        onclick="openParticipantModalFromDetail()" type="button"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                        <span id="modalJoinButtonText">Inschrijven</span>
                        <svg id="modalJoinSpinner" class="hidden animate-spin ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Participant Type Modal -->
    <div id="participantModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Inschrijven voor activiteit</h3>
                    <button onclick="closeParticipantModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 mb-6">Vul uw gegevens in.</p>
                <form id="participantForm" method="POST" action="">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Voornaam</label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Achternaam</label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" id="participantFormSubmit"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center justify-center">
                            <span id="participantFormText">inschrijven</span>
                            <svg id="participantFormSpinner" class="hidden animate-spin ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                <button onclick="closeParticipantModal()"
                        class="w-full mt-4 px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    Annuleren
                </button>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()"
                    class="absolute -top-4 -right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-100 z-10">
                ×
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        let selectedActivityId = null;
        let imageCounters = {};
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
            primary_image_url: @json($activity->primary_image_url),
            total_participants: {{ $activity->users->count() + $activity->externals->count() }},
            is_enrolled: {{ auth()->check() && $activity->users->contains(auth()->id()) ? 'true' : 'false' }}
        };

        @if($activity->images->count() > 1)
        imageCounters[{{ $activity->id }}] = 0;
        @endif
        @endforeach

        window.joinActivityDirectly = function(activityId) {
            const button = document.getElementById(`join-btn-${activityId}`);
            if (button) {
                const textSpan = button.querySelector('.join-btn-text');
                const spinner = button.querySelector('.join-btn-spinner');
                button.disabled = true;
                button.classList.add('opacity-75', 'cursor-not-allowed');
                if (textSpan) textSpan.classList.add('hidden');
                if (spinner) spinner.classList.remove('hidden');
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/activities/${activityId}/join`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            document.body.appendChild(form);
            form.submit();
        };

        window.nextImage = function(activityId) {
            const carousel = document.getElementById(`carousel-${activityId}`);
            const images = carousel.querySelectorAll('.carousel-image');
            const counter = document.getElementById(`counter-${activityId}`);

            images[imageCounters[activityId]].classList.add('hidden');
            imageCounters[activityId] = (imageCounters[activityId] + 1) % images.length;
            images[imageCounters[activityId]].classList.remove('hidden');
            counter.textContent = imageCounters[activityId] + 1;
        };

        window.previousImage = function(activityId) {
            const carousel = document.getElementById(`carousel-${activityId}`);
            const images = carousel.querySelectorAll('.carousel-image');
            const counter = document.getElementById(`counter-${activityId}`);

            images[imageCounters[activityId]].classList.add('hidden');
            imageCounters[activityId] = (imageCounters[activityId] - 1 + images.length) % images.length;
            images[imageCounters[activityId]].classList.remove('hidden');
            counter.textContent = imageCounters[activityId] + 1;
        };

        window.openActivityModal = function(activityId) {
            const activity = activitiesData[activityId];
            if (!activity) return;

            selectedActivityId = activityId;
            document.getElementById('modalActivityTitle').textContent = activity.name;

            let modalContent = '';
            modalContent += '<div class="mb-6">';
            if (activity.images.length > 0) {
                if (activity.images.length === 1) {
                    modalContent += `
                        <div class="w-full h-64 md:h-80 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                            <img src="${activity.images[0].url}" alt="${activity.images[0].name}"
                                 class="max-h-full max-w-full object-contain">
                        </div>
                    `;
                } else {
                    modalContent += `
                        <div id="modal-carousel" class="relative rounded-lg overflow-hidden bg-gray-100">
                            <div id="modal-carousel-track" class="relative w-full h-64 md:h-80">
                                ${activity.images.map((img, i) => `
                                    <img
                                        src="${img.url}"
                                        alt="${img.name ?? activity.name}"
                                        class="absolute inset-0 w-full h-full object-contain transition-opacity duration-300 ${i === 0 ? 'opacity-100' : 'opacity-0'}"
                                        data-slide="${i}">
                                `).join('')}
                            </div>

                            <button type="button"
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center"
                                onclick="modalCarouselPrev()" aria-label="Vorige">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>

                            <button type="button"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center"
                                onclick="modalCarouselNext()" aria-label="Volgende">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <div id="modal-carousel-dots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
                                ${activity.images.map((_, i) => `
                                    <button type="button"
                                        class="w-2.5 h-2.5 rounded-full ${i === 0 ? 'bg-white' : 'bg-white/50'} ring-1 ring-white/50 hover:bg-white"
                                        onclick="modalCarouselGoTo(${i})" aria-label="Ga naar afbeelding ${i+1}">
                                    </button>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
            } else {
                modalContent += `
                    <div class="w-full h-64 md:h-80 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                        <img src="${activity.primary_image_url}" alt="${activity.name}"
                             class="max-h-full max-w-full object-contain">
                    </div>
                `;
            }
            modalContent += '</div>';

            modalContent += '<div class="grid md:grid-cols-2 gap-8 mb-8">';
            modalContent += '<div class="space-y-4">';
            modalContent += `<div><strong class="text-gray-700">Locatie:</strong> <span class="ml-2">${activity.location}</span></div>`;
            modalContent += `<div><strong class="text-gray-700">Starttijd:</strong> <span class="ml-2">${activity.start_time}</span></div>`;
            if (activity.end_time) modalContent += `<div><strong class="text-gray-700">Eindtijd:</strong> <span class="ml-2">${activity.end_time}</span></div>`;
            modalContent += `<div><strong class="text-gray-700">Kosten:</strong> <span class="ml-2">${activity.cost > 0 ? '€' + activity.cost.toFixed(2).replace('.', ',') : 'Gratis'}</span></div>`;
            modalContent += '</div>';

            modalContent += '<div class="space-y-4">';
            if (activity.includes_food) modalContent += `<div>Eten inbegrepen</div>`;
            if (activity.max_participants) modalContent += `<div><strong class="text-gray-700">Maximaal aantal deelnemers:</strong> <span class="ml-2">${activity.max_participants}</span></div>`;
            if (activity.min_participants) modalContent += `<div><strong class="text-gray-700">Minimaal aantal deelnemers:</strong> <span class="ml-2">${activity.min_participants}</span></div>`;
            modalContent += `<div><strong class="text-gray-700">Huidige deelnemers:</strong> <span class="ml-2">${activity.total_participants}</span></div>`;
            modalContent += '</div></div>';

            if (activity.description) modalContent += `<div class="mb-8"><h4 class="text-xl font-semibold mb-3">Beschrijving</h4><p class="text-gray-700 leading-relaxed">${activity.description}</p></div>`;
            if (activity.requirements) modalContent += `<div class="mb-8"><h4 class="text-xl font-semibold mb-3">Vereisten</h4><p class="text-gray-700 leading-relaxed">${activity.requirements}</p></div>`;

            document.getElementById('modalContent').innerHTML = modalContent;

            const joinButton = document.getElementById('modalJoinButton');
            const joinButtonText = document.getElementById('modalJoinButtonText');
            const isFull = {{ $activity->max_participants && ($activity->users->count() + $activity->externals()->wherePivot('confirmed', true)->count()) >= $activity->max_participants ? 'true' : 'false' }};

            if (activity.is_enrolled) {
                joinButtonText.textContent = 'Uitschrijven';
                joinButton.className = 'px-6 py-2 bg-red-600 text-white rounded-lg cursor-pointer inline-flex items-center';
                joinButton.disabled = false;
                joinButton.onclick = function(e) {
                    e.stopPropagation();
                    window.leaveActivityFromModal();
                };
            } else if (isFull) {
                joinButtonText.textContent = 'Vol';
                joinButton.className = 'px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed inline-flex items-center';
                joinButton.disabled = true;
            } else {
                joinButtonText.textContent = 'Inschrijven';
                joinButton.className = 'px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center';
                joinButton.disabled = false;
                joinButton.onclick = function() { openParticipantModalFromDetail(); };
            }

            if (activity.images.length > 1) {
                initModalCarousel(activity.images.length);
            }

            document.getElementById('activityModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
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

        window.closeActivityModal = function() {
            const modal = document.getElementById('activityModal');
            if (!modal) return;
            document.removeEventListener('keydown', modalCarouselKeyHandler);
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        window.openParticipantModal = function(activityId) {
            selectedActivityId = activityId;
            const form = document.getElementById('participantForm');
            form.action = `/activities/${activityId}/join`;
            document.getElementById('participantModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.openParticipantModalFromDetail = function() {
            if (!selectedActivityId) return;
            @auth
            const modalButton = document.getElementById('modalJoinButton');
            const modalButtonText = document.getElementById('modalJoinButtonText');
            const modalSpinner = document.getElementById('modalJoinSpinner');
            modalButton.disabled = true;
            modalButton.classList.add('opacity-75', 'cursor-not-allowed');
            modalButtonText.textContent = 'Bezig met inschrijven...';
            modalSpinner.classList.remove('hidden');
            joinActivityDirectly(selectedActivityId);
            @else
            openParticipantModal(selectedActivityId);
            @endauth
        };

        window.closeParticipantModal = function() {
            document.getElementById('participantModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
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

        document.addEventListener('DOMContentLoaded', function () {
            const sortButton = document.getElementById('sortDropdownButton');
            const sortMenu = document.getElementById('sortDropdownMenu');

            if (sortButton && sortMenu) {
                sortButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    sortMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (!sortButton.contains(e.target) && !sortMenu.contains(e.target)) {
                        sortMenu.classList.add('hidden');
                    }
                });
            }

            const activityModal = document.getElementById('activityModal');
            const participantModal = document.getElementById('participantModal');
            const imageModal = document.getElementById('imageModal');

            if (activityModal) {
                activityModal.addEventListener('click', function (e) {
                    if (e.target === this) closeActivityModal();
                });
            }
            if (participantModal) {
                participantModal.addEventListener('click', function (e) {
                    if (e.target === this) closeParticipantModal();
                });
            }
            if (imageModal) {
                imageModal.addEventListener('click', function (e) {
                    if (e.target === this) closeImageModal();
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    if (activityModal && !activityModal.classList.contains('hidden')) {
                        closeActivityModal();
                    } else if (participantModal && !participantModal.classList.contains('hidden')) {
                        closeParticipantModal();
                    } else if (imageModal && !imageModal.classList.contains('hidden')) {
                        closeImageModal();
                    } else {
                        const sortMenu = document.getElementById('sortDropdownMenu');
                        if (sortMenu) sortMenu.classList.add('hidden');
                    }
                }
            });
        });

        // Modal carousel
        let __modalCarousel = { index: 0, total: 0, touchStartX: null };

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
    </script>
</x-app-layout>
