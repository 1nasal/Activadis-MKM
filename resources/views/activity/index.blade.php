<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('activities.create') }}" class="inline-block px-6 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                        {{ __("Create Activity") }}
                    </a>
                </div>
                <div class="p-6">
                    @if($activities->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Location</th>
                                    <th class="px-4 py-2">Start-time</th>
                                    <th class="px-4 py-2">End-time</th>
                                    <th class="px-4 py-2">Actions</th>
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
                                            <a href="{{ route('activities.show', $activity) }}" class="text-blue-600 hover:underline">Show more</a>
                                            <a href="{{ route('activities.edit', $activity) }}" class="text-yellow-600 hover:underline ml-2">Edit</a>
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