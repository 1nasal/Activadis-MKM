{{-- filepath: resources/views/activity/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Activity') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('activities.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium" for="name">Name</label>
                        <input class="w-full border rounded p-2" type="text" name="name" id="name" value="{{ old('name') }}" required>
                        @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="location">Location</label>
                        <input class="w-full border rounded p-2" type="text" name="location" id="location" value="{{ old('location') }}" required>
                        @error('location') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="includes_food">Includes Food</label>
                        <select class="w-full border rounded p-2" name="includes_food" id="includes_food" required>
                            <option value="1" {{ old('includes_food') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('includes_food') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('includes_food') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="description">Description</label>
                        <textarea class="w-full border rounded p-2" name="description" id="description" required>{{ old('description') }}</textarea>
                        @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="start_time">Start Time</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
                        @error('start_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="end_time">End Time</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
                        @error('end_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="cost">Cost</label>
                        <input class="w-full border rounded p-2" type="number" step="0.01" name="cost" id="cost" value="{{ old('cost') }}" required>
                        @error('cost') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="max_participants">Max Participants</label>
                        <input class="w-full border rounded p-2" type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}" required>
                        @error('max_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="min_participants">Min Participants</label>
                        <input class="w-full border rounded p-2" type="number" name="min_participants" id="min_participants" value="{{ old('min_participants') }}" required>
                        @error('min_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="image">Image (URL)</label>
                        <input class="w-full border rounded p-2" type="text" name="image" id="image" value="{{ old('image') }}">
                        @error('image') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="requirements">Requirements</label>
                        <textarea class="w-full border rounded p-2" name="requirements" id="requirements">{{ old('requirements') }}</textarea>
                        @error('requirements') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Create Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>