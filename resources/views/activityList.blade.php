<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activiteiten - Covadis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <x-header />

    <div class="max-w-6xl mx-auto py-8">
        <header class="mb-10">
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Aankomende Activiteiten</h1>
            <p class="text-gray-600">Overzicht van alle geplande activiteiten</p>
        </header>

        @if($activities->count() > 0)
            <div class="space-y-4">
                @foreach($activities as $activity)
                    <article class="bg-white border border-gray-200 p-6 hover:border-gray-300 transition-colors">
                        <div class="flex flex-col md:flex-row gap-6">
                            
                            <!-- Activity Images with Navigation -->
                            @if($activity->images->count() > 0 || $activity->primary_image)
                                <div class="md:w-64 flex-shrink-0 relative">
                                    @if($activity->images->count() > 1)
                                        <!-- Image carousel for multiple images -->
                                        <div class="relative group">
                                            <div class="image-carousel" id="carousel-{{ $activity->id }}">
                                                @foreach($activity->images as $index => $image)
                                                    <img src="{{ $image->url }}" 
                                                         alt="{{ $image->original_name }}" 
                                                         class="w-full h-48 object-cover rounded-lg border carousel-image {{ $index === 0 ? '' : 'hidden' }}"
                                                         data-index="{{ $index }}">
                                                @endforeach
                                            </div>
                                            
                                            <!-- Navigation buttons -->
                                            <button onclick="previousImage({{ $activity->id }})" 
                                                    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-opacity-75">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            <button onclick="nextImage({{ $activity->id }})" 
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-opacity-75">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Image counter -->
                                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span id="counter-{{ $activity->id }}">1</span>/{{ $activity->images->count() }}
                                            </div>
                                        </div>
                                    @elseif($activity->images->count() === 1)
                                        <!-- Single uploaded image -->
                                        <img src="{{ $activity->images->first()->url }}" 
                                             alt="{{ $activity->images->first()->original_name }}" 
                                             class="w-full h-48 object-cover rounded-lg border">
                                    @elseif($activity->primary_image)
                                        <!-- Fallback to URL image -->
                                        <img src="{{ $activity->primary_image }}" 
                                             alt="{{ $activity->name }}" 
                                             class="w-full h-48 object-cover rounded-lg border">
                                    @endif
                                </div>
                            @endif
                            
                            <div class="md:w-32 flex-shrink-0">
                                <div class="text-sm text-gray-500 mb-1">
                                    {{ $activity->start_time->format('j M') }}
                                </div>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $activity->start_time->format('H:i') }}
                                </div>
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
                                    <div>📍 {{ $activity->location }}</div>
                                    
                                    @php
                                        $totalParticipants = $activity->users->count() + $activity->externals->count();
                                    @endphp
                                    
                                    <div>👥 {{ $totalParticipants }} deelnemers
                                        @if($activity->max_participants)
                                            (max {{ $activity->max_participants }})
                                        @endif
                                    </div>
                                    
                                    @if($activity->includes_food)
                                        <div>🍕 Eten inbegrepen</div>
                                    @endif
                                </div>

                                <p class="text-gray-700 text-sm leading-relaxed mb-4">
                                    {{ $activity->description }}
                                </p>
                            </div>

                            <div class="md:w-32 flex-shrink-0 flex md:flex-col gap-2">
                                @php $isFull = $activity->max_participants && $totalParticipants >= $activity->max_participants; @endphp
                                
                                <button 
                                    onclick="openParticipantModal({{ $activity->id }})"
                                    class="px-4 py-2 text-sm font-medium border transition-colors {{ $isFull ? 'border-gray-300 text-gray-500 cursor-not-allowed' : 'border-blue-600 text-blue-600 hover:bg-blue-50' }}"
                                    {{ $isFull ? 'disabled' : '' }}>
                                    {{ $isFull ? 'Vol' : 'Deelnemen' }}
                                </button>
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
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Geen activiteiten gepland</h3>
                <p class="text-gray-600">Er zijn momenteel geen aankomende activiteiten.</p>
            </div>
        @endif
    </div>

    <!-- Participant Type Modal -->
    <div id="participantModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Deelnemen aan activiteit</h3>
                    <button onclick="closeParticipantModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-gray-600 mb-6">Bent u een medewerker van Covadis of een externe deelnemer?</p>
                
                <div class="space-y-3">
                    <button 
                        onclick="selectParticipantType('employee')"
                        class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Ik ben een medewerker
                    </button>
                    
                    <button 
                        onclick="selectParticipantType('external')"
                        class="w-full px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Ik ben een externe deelnemer
                    </button>
                </div>
                
                <button 
                    onclick="closeParticipantModal()"
                    class="w-full mt-4 px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    Annuleren
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedActivityId = null;
        let imageCounters = {};

        // Initialize image counters for activities with multiple images
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($activities as $activity)
                @if($activity->images->count() > 1)
                    imageCounters[{{ $activity->id }}] = 0;
                @endif
            @endforeach
        });

        function nextImage(activityId) {
            const carousel = document.getElementById(`carousel-${activityId}`);
            const images = carousel.querySelectorAll('.carousel-image');
            const counter = document.getElementById(`counter-${activityId}`);
            
            // Hide current image
            images[imageCounters[activityId]].classList.add('hidden');
            
            // Move to next image
            imageCounters[activityId] = (imageCounters[activityId] + 1) % images.length;
            
            // Show next image
            images[imageCounters[activityId]].classList.remove('hidden');
            
            // Update counter
            counter.textContent = imageCounters[activityId] + 1;
        }

        function previousImage(activityId) {
            const carousel = document.getElementById(`carousel-${activityId}`);
            const images = carousel.querySelectorAll('.carousel-image');
            const counter = document.getElementById(`counter-${activityId}`);
            
            // Hide current image
            images[imageCounters[activityId]].classList.add('hidden');
            
            // Move to previous image
            imageCounters[activityId] = (imageCounters[activityId] - 1 + images.length) % images.length;
            
            // Show previous image
            images[imageCounters[activityId]].classList.remove('hidden');
            
            // Update counter
            counter.textContent = imageCounters[activityId] + 1;
        }

        function openParticipantModal(activityId) {
            selectedActivityId = activityId;
            document.getElementById('participantModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeParticipantModal() {
            document.getElementById('participantModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            selectedActivityId = null;
        }

        function selectParticipantType(type) {
            if (type === 'employee') {
                // Check if user is logged in
                @auth
                    // User is logged in as employee, handle participation
                    console.log('Employee participation for activity:', selectedActivityId);
                    // TODO: Add employee participation logic here
                    alert('Functionaliteit voor medewerkers wordt binnenkort toegevoegd!');
                @else
                    // User is not logged in, redirect to login
                    window.location.href = '{{ route("login") }}';
                @endauth
            } else if (type === 'external') {
                // Handle external participation
                console.log('External participation for activity:', selectedActivityId);
                // TODO: Add external participation logic here
                alert('Functionaliteit voor externe deelnemers wordt binnenkort toegevoegd!');
            }
            
            closeParticipantModal();
        }

        // Close modal when clicking outside
        document.getElementById('participantModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeParticipantModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('participantModal').classList.contains('hidden')) {
                closeParticipantModal();
            }
        });
    </script>

</body>
</html>