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

                    <div class="mt-6">
                        @if($activities->count())
                            <ul class="space-y-3">
                                @foreach($activities as $activity)
                                    <li>
                                        <a href="{{ route('activities.show', $activity) }}" 
                                           class="block px-6 py-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 transition duration-200 ease-in-out shadow-sm hover:shadow-md">
                                            <div class="flex items-center justify-between">
                                                <span class="text-base font-semibold text-gray-900">
                                                    {{ $activity->name }}
                                                </span>
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                                Er zijn nog geen activiteiten.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>