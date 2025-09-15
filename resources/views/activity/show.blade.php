<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activiteit details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-4">{{ $activity->name }}</h3>
                <ul class="mb-6">
                    <li><strong>Locatie:</strong> {{ $activity->location }}</li>
                    <li><strong>Inclusief eten:</strong> {{ $activity->includes_food ? 'Ja' : 'Nee' }}</li>
                    <li><strong>Beschrijving:</strong> {{ $activity->description }}</li>
                    <li><strong>Starttijd:</strong> {{ $activity->start_time }}</li>
                    <li><strong>Eindtijd:</strong> {{ $activity->end_time }}</li>
                    <li><strong>Kosten:</strong> €{{ number_format($activity->cost, 2, ',', '.') }}</li>
                    <li><strong>Maximaal aantal deelnemers:</strong> {{ $activity->max_participants ?? '-' }}</li>
                    <li><strong>Minimaal aantal deelnemers:</strong> {{ $activity->min_participants ?? '-' }}</li>
                    <li><strong>Afbeelding:</strong> {{ $activity->image ?? '-' }}</li>
                    <li><strong>Vereisten:</strong> {{ $activity->requirements ?? '-' }}</li>
                </ul>
                <div class="flex space-x-4">
                    <a href="{{ route('activities.edit', $activity) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Bewerk</a>
                    <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze activiteit wilt verwijderen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Verwijder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>