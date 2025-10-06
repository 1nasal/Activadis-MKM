{{-- filepath: resources/views/activity/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bewerk Activiteit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('activities.update', $activity) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium" for="name">Naam</label>
                        <input class="w-full border rounded p-2" type="text" name="name" id="name" value="{{ old('name', $activity->name) }}" required>
                        @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="location">Locatie</label>
                        <input class="w-full border rounded p-2" type="text" name="location" id="location" value="{{ old('location', $activity->location) }}" required>
                        @error('location') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="includes_food">Inclusief Eten</label>
                        <select class="w-full border rounded p-2" name="includes_food" id="includes_food" required>
                            <option value="1" {{ old('includes_food', $activity->includes_food) == '1' ? 'selected' : '' }}>Ja</option>
                            <option value="0" {{ old('includes_food', $activity->includes_food) == '0' ? 'selected' : '' }}>Nee</option>
                        </select>
                        @error('includes_food') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="description">Beschrijving</label>
                        <textarea class="w-full border rounded p-2" name="description" id="description" required>{{ old('description', $activity->description) }}</textarea>
                        @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="start_time">Starttijd</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($activity->start_time)->format('Y-m-d\TH:i')) }}" required>
                        @error('start_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="end_time">Eindtijd</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($activity->end_time)->format('Y-m-d\TH:i')) }}" required>
                        @error('end_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="cost">Kosten</label>
                        <input class="w-full border rounded p-2" type="number" step="0.01" name="cost" id="cost" value="{{ old('cost', $activity->cost) }}" required>
                        @error('cost') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="max_participants">Maximaal Aantal Deelnemers</label>
                        <input class="w-full border rounded p-2" type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $activity->max_participants) }}">
                        @error('max_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="min_participants">Minimaal Aantal Deelnemers</label>
                        <input class="w-full border rounded p-2" type="number" name="min_participants" id="min_participants" value="{{ old('min_participants', $activity->min_participants) }}">
                        @error('min_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <!-- Existing images -->
                    @if($activity->images->count() > 0)
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Huidige Afbeeldingen</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($activity->images as $image)
                                    <div class="relative group">
                                        <img src="{{ $image->url }}" alt="{{ $image->original_name }}" class="w-full h-24 object-cover rounded border">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                                            <label class="flex items-center space-x-2 text-white cursor-pointer">
                                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="form-checkbox">
                                                <span class="text-sm">Verwijderen</span>
                                            </label>
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1 truncate">{{ $image->original_name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New images upload -->
                    <div class="mb-4">
                        <label class="block font-medium mb-2" for="images">Nieuwe Afbeeldingen Toevoegen</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6" id="upload-area">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <label for="images" class="cursor-pointer">
                                        <span class="mt-2 block text-sm font-medium text-gray-900">
                                            Sleep afbeeldingen hierheen of 
                                            <span class="text-blue-600 underline">klik om te selecteren</span>
                                        </span>
                                        <input type="file" name="images[]" id="images" class="sr-only" multiple accept="image/*">
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF tot 2MB per afbeelding</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preview area for selected images -->
                        <div id="image-previews" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden"></div>
                        
                        @error('images.*') <div class="text-red-500 text-sm mt-2">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="requirements">Vereisten</label>
                        <textarea class="w-full border rounded p-2" name="requirements" id="requirements">{{ old('requirements', $activity->requirements) }}</textarea>
                        @error('requirements') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Activiteit Bijwerken
                        </button>
                        <a href="{{ route('activities.show', $activity) }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors">
                            Annuleren
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('images');
            const previewArea = document.getElementById('image-previews');
            let selectedFiles = [];

            // Click to select files
            uploadArea.addEventListener('click', () => fileInput.click());

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('bg-blue-50', 'border-blue-300');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('bg-blue-50', 'border-blue-300');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('bg-blue-50', 'border-blue-300');
                
                const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                handleFiles(files);
            });

            // Handle file selection
            fileInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                handleFiles(files);
            });

            function handleFiles(files) {
                selectedFiles = [...selectedFiles, ...files];
                updateFileInput();
                showPreviews();
            }

            function updateFileInput() {
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
            }

            function showPreviews() {
                previewArea.innerHTML = '';
                previewArea.classList.remove('hidden');

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-24 object-cover rounded border">
                            <button type="button" onclick="removeImage(${index})" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                ×
                            </button>
                            <div class="text-xs text-gray-600 mt-1 truncate">${file.name}</div>
                        `;
                        previewArea.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }

            window.removeImage = function(index) {
                selectedFiles.splice(index, 1);
                updateFileInput();
                if (selectedFiles.length === 0) {
                    previewArea.classList.add('hidden');
                } else {
                    showPreviews();
                }
            };
        });
    </script>
</x-app-layout>