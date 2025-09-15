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
                <form method="POST" action="{{ route('activities.update', $activity) }}">
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

                    <div class="mb-4">
                        <label class="block font-medium" for="image">Afbeelding (URL)</label>
                        <input class="w-full border rounded p-2" type="text" name="image" id="image" value="{{ old('image', $activity->image) }}">
                        @error('image') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="requirements">Vereisten</label>
                        <textarea class="w-full border rounded p-2" name="requirements" id="requirements">{{ old('requirements', $activity->requirements) }}</textarea>
                        @error('requirements') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex items-center">
                        <form method="POST" action="{{ route('activities.update', $activity) }}">
                            @csrf
                            @method('PUT')
                            <!-- ...alle update velden... -->
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Activiteit Bijwerken
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>