<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activiteit details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                
                <!-- Activity Images -->
                @if($activity->images->count() > 0 || $activity->image)
                    <div class="mb-6">
                        @if($activity->images->count() > 0)
                            @if($activity->images->count() === 1)
                                <img src="{{ $activity->images->first()->url }}" 
                                     alt="{{ $activity->name }}" 
                                     class="w-full h-64 object-cover">
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                                    @foreach($activity->images as $image)
                                        <div class="group cursor-pointer" onclick="openImageModal('{{ $image->url }}', '{{ $image->original_name }}')">
                                            <img src="{{ $image->url }}" 
                                                 alt="{{ $image->original_name }}" 
                                                 class="w-full h-48 object-cover rounded-lg group-hover:opacity-90 transition-opacity">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($activity->image)
                            <img src="{{ $activity->image }}" 
                                 alt="{{ $activity->name }}" 
                                 class="w-full h-64 object-cover">
                        @endif
                    </div>
                @endif

                <div class="p-8">
                    <h3 class="text-3xl font-bold mb-6">{{ $activity->name }}</h3>
                    
                    <div class="grid md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-4">
                            <div>
                                <strong class="text-gray-700">Locatie:</strong>
                                <span class="ml-2">{{ $activity->location }}</span>
                            </div>
                            
                            <div>
                                <strong class="text-gray-700">Starttijd:</strong>
                                <span class="ml-2">{{ $activity->start_time->format('d-m-Y H:i') }}</span>
                            </div>
                            
                            <div>
                                <strong class="text-gray-700">Eindtijd:</strong>
                                <span class="ml-2">{{ $activity->end_time->format('d-m-Y H:i') }}</span>
                            </div>
                            
                            <div>
                                <strong class="text-gray-700">Kosten:</strong>
                                <span class="ml-2">
                                    @if($activity->cost > 0)
                                        €{{ number_format($activity->cost, 2, ',', '.') }}
                                    @else
                                        Gratis
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if($activity->includes_food)
                                <div>
                                    <span>Eten inbegrepen</span>
                                </div>
                            @endif
                            
                            @if($activity->max_participants)
                                <div>
                                    <strong class="text-gray-700">Maximaal aantal deelnemers:</strong>
                                    <span class="ml-2">{{ $activity->max_participants }}</span>
                                </div>
                            @endif
                            
                            @if($activity->min_participants)
                                <div>
                                    <strong class="text-gray-700">Minimaal aantal deelnemers:</strong>
                                    <span class="ml-2">{{ $activity->min_participants }}</span>
                                </div>
                            @endif
                            
                            @php
                                $totalParticipants = $activity->users->count() + $activity->externals->count();
                            @endphp
                            <div>
                                <strong class="text-gray-700">Aantal deelnemers:</strong>
                                <span class="ml-2">{{ $totalParticipants }}
                                    @if($activity->max_participants)
                                        van {{ $activity->max_participants }}
                                    @endif
                                </span>
                            </div>
                            
                            @if($activity->images->count() > 1)
                                <div>
                                    <strong class="text-gray-700">Afbeeldingen:</strong>
                                    <span class="ml-2">{{ $activity->images->count() }} afbeeldingen beschikbaar</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($activity->description)
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-3">Beschrijving</h4>
                            <p class="text-gray-700 leading-relaxed">{{ $activity->description }}</p>
                        </div>
                    @endif
                    
                    @if($activity->requirements)
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-3">Vereisten</h4>
                            <p class="text-gray-700 leading-relaxed">{{ $activity->requirements }}</p>
                        </div>
                    @endif

                    <!-- Participants Section -->
                    @if($totalParticipants > 0)
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-3">Deelnemers ({{ $totalParticipants }})</h4>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                @if($activity->users->count() > 0)
                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Geregistreerde gebruikers</h5>
                                        <ul class="space-y-2">
                                            @foreach($activity->users as $user)
                                                <li class="flex items-start text-gray-700">
                                                    <svg class="w-5 h-5 mr-2 mt-0.5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                                                            @if($user->job_title)
                                                                <span class="text-xs px-2 py-0.5 bg-green-100 text-green-800 rounded">{{ $user->job_title }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($activity->externals->count() > 0)
                                    <div>
                                        <h5 class="font-medium text-gray-700 mb-2">Externe deelnemers</h5>
                                        <ul class="space-y-2">
                                            @foreach($activity->externals as $external)
                                                <li class="flex items-start text-gray-700">
                                                    <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <span>{{ $external->first_name }} {{ $external->last_name }}</span>
                                                            <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded">Extern</span>
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $external->email }}</div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-3">Deelnemers</h4>
                            <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500">
                                Nog geen deelnemers ingeschreven
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('activities.edit', $activity) }}" class="bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition-colors">
                            Bewerk Activiteit
                        </a>
                        <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze activiteit wilt verwijderen?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                                Verwijder Activiteit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal for viewing full-size images -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-100 z-10">
                ×
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        function openImageModal(imageUrl, imageName) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImage').alt = imageName;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>