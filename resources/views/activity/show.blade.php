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
                            <!-- Multiple uploaded images -->
                            @if($activity->images->count() === 1)
                                <img src="{{ $activity->images->first()->url }}" 
                                     alt="{{ $activity->name }}" 
                                     class="w-full h-64 object-cover">
                            @else
                                <!-- Image gallery for multiple images -->
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
                            <!-- Single image from URL field -->
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

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>