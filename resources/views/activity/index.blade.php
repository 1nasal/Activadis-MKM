<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activiteit') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <x-link-button href="{{ route('activities.create') }}">
                        {{ __("Activiteit aanmaken") }}
                    </x-link-button>

                    <div class="overflow-x-auto mt-6">
                        @if($activities->count())
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                            Naam
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                            Locatie
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                            Starttijd
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                            Eindtijd
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($activities as $activity)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $activity->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $activity->location }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $activity->start_time }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $activity->end_time }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 space-x-2">
                                                <x-link-button href="{{ route('activities.show', $activity) }}">
                                                    Meer weergeven
                                                </x-link-button>
                                                <x-link-button href="{{ route('activities.edit', $activity) }}" class="bg-yellow-600 hover:bg-yellow-700">
                                                    Bewerken
                                                </x-link-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="px-6 py-4 text-sm text-gray-500">
                                Er zijn nog geen activiteiten.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>