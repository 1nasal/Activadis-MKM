<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activiteit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('activities.create') }}" class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                        {{ __("Activiteit aanmaken") }}
                    </a>
                </div>
                <div class="p-6">
                    @if($activities->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Naam</th>
                                    <th class="px-4 py-2">Locatie</th>
                                    <th class="px-4 py-2">Starttijd</th>
                                    <th class="px-4 py-2">Eindtijd</th>
                                    <th class="px-4 py-2">Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $activity->name }}</td>
                                        <td class="border px-4 py-2">{{ $activity->location }}</td>
                                        <td class="border px-4 py-2">{{ $activity->start_time }}</td>
                                        <td class="border px-4 py-2">{{ $activity->end_time }}</td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('activities.show', $activity) }}" class="text-blue-600 hover:underline">Meer weergeven</a>
                                            <a href="{{ route('activities.edit', $activity) }}" class="text-yellow-600 hover:underline ml-2">Bewerken</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">Er zijn nog geen activiteiten.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>