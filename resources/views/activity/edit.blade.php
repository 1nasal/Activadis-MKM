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
                <form method="POST" action="{{ route('activities.update', $activity) }}" enctype="multipart/form-data" id="activity-form">
                    @csrf
                    @method('PUT')

                    {{-- Naam --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="name">Naam</label>
                        <input class="w-full border rounded p-2" type="text" name="name" id="name"
                               value="{{ old('name', $activity->name) }}" required>
                        @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Locatie --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="location">Locatie</label>
                        <input class="w-full border rounded p-2" type="text" name="location" id="location"
                               value="{{ old('location', $activity->location) }}" required>
                        @error('location') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- ✅ Inclusief eten als checkbox --}}
                    <div class="mb-4">
                        <input type="hidden" name="includes_food" value="0">
                        <label class="inline-flex items-center gap-2">
                            <input class="w-4 h-4" type="checkbox" name="includes_food" id="includes_food" value="1"
                                   {{ old('includes_food', $activity->includes_food) ? 'checked' : '' }}>
                            <span class="font-medium">Inclusief eten</span>
                        </label>
                        @error('includes_food') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Beschrijving --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="description">Beschrijving</label>
                        <textarea class="w-full border rounded p-2" name="description" id="description" required>{{ old('description', $activity->description) }}</textarea>
                        @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- ✅ Starttijd (niet in het verleden) --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="start_time">Starttijd</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="start_time" id="start_time"
                               value="{{ old('start_time', \Carbon\Carbon::parse($activity->start_time)->format('Y-m-d\TH:i')) }}"
                               required min="{{ now()->format('Y-m-d\TH:i') }}">
                        <div id="start-error" class="text-red-500 text-sm hidden mt-1"></div>
                        @error('start_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- ✅ Eindtijd (niet in het verleden, niet vóór starttijd) --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="end_time">Eindtijd</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="end_time" id="end_time"
                               value="{{ old('end_time', \Carbon\Carbon::parse($activity->end_time)->format('Y-m-d\TH:i')) }}"
                               required min="{{ now()->format('Y-m-d\TH:i') }}">
                        <div id="time-error" class="text-red-500 text-sm hidden mt-1"></div>
                        @error('end_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Kosten --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="cost">Kosten</label>
                        <input class="w-full border rounded p-2" type="number" step="0.01" name="cost" id="cost"
                               value="{{ old('cost', $activity->cost) }}" required>
                        @error('cost') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- ✅ Deelnemers-validatie (rode tekstmelding) --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="max_participants">Maximaal Aantal Deelnemers</label>
                        <input class="w-full border rounded p-2" type="number" name="max_participants" id="max_participants"
                               min="1" value="{{ old('max_participants', $activity->max_participants) }}">
                        @error('max_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="min_participants">Minimaal Aantal Deelnemers</label>
                        <input class="w-full border rounded p-2" type="number" name="min_participants" id="min_participants"
                               min="1" value="{{ old('min_participants', $activity->min_participants) }}">
                        <div id="participant-error" class="text-red-500 text-sm hidden mt-1"></div>
                        @error('min_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Afbeeldingen (ongewijzigd) --}}
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

                    {{-- Vereisten --}}
                    <div class="mb-4">
                        <label class="block font-medium" for="requirements">Vereisten</label>
                        <textarea class="w-full border rounded p-2" name="requirements" id="requirements">{{ old('requirements', $activity->requirements) }}</textarea>
                        @error('requirements') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Knoppen --}}
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

    {{-- ✅ Live validatie script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startInput = document.getElementById('start_time');
            const endInput = document.getElementById('end_time');
            const timeError = document.getElementById('time-error');
            const startError = document.getElementById('start-error');
            const minInput = document.getElementById('min_participants');
            const maxInput = document.getElementById('max_participants');
            const participantError = document.getElementById('participant-error');
            const form = document.getElementById('activity-form');

            function validateTimes() {
                const start = new Date(startInput.value);
                const end = new Date(endInput.value);
                const now = new Date();
                let valid = true;

                startError.classList.add('hidden');
                timeError.classList.add('hidden');

                if (start < now) {
                    startError.textContent = "De starttijd mag niet in het verleden liggen.";
                    startError.classList.remove('hidden');
                    valid = false;
                }

                if (end < now) {
                    timeError.textContent = "De eindtijd mag niet in het verleden liggen.";
                    timeError.classList.remove('hidden');
                    valid = false;
                } else if (end <= start) {
                    timeError.textContent = "De eindtijd moet later zijn dan de starttijd.";
                    timeError.classList.remove('hidden');
                    valid = false;
                }

                return valid;
            }

            function validateParticipants() {
                const min = parseInt(minInput.value);
                const max = parseInt(maxInput.value);
                participantError.classList.add('hidden');

                if (min && max && min > max) {
                    participantError.textContent = "Het minimale aantal deelnemers kan niet groter zijn dan het maximale aantal.";
                    participantError.classList.remove('hidden');
                    return false;
                }
                return true;
            }

            startInput.addEventListener('input', validateTimes);
            endInput.addEventListener('input', validateTimes);
            minInput.addEventListener('input', validateParticipants);
            maxInput.addEventListener('input', validateParticipants);

            form.addEventListener('submit', (e) => {
                if (!validateTimes() || !validateParticipants()) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>
