<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activiteiten - Covadis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

<div class="max-w-6xl mx-auto px-6 py-8">
    <header class="mb-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Aankomende Activiteiten</h1>
        <p class="text-gray-600">Overzicht van alle geplande activiteiten</p>
    </header>

    @if($activities->count() > 0)
        <div class="space-y-4">
            @foreach($activities as $activity)
                <article class="bg-white border border-gray-200 p-6 hover:border-gray-300 transition-colors">
                    <div class="flex flex-col md:flex-row gap-6">
                        
                        <div class="md:w-32 flex-shrink-0">
                            <div class="text-sm text-gray-500 mb-1">
                                {{ $activity->start_time->format('j M') }}
                            </div>
                            <div class="text-lg font-medium text-gray-900">
                                {{ $activity->start_time->format('H:i') }}
                            </div>
                            @if($activity->cost > 0)
                                <div class="text-sm font-medium text-blue-600 mt-2">
                                    €{{ number_format($activity->cost, 2) }}
                                </div>
                            @else
                                <div class="text-sm font-medium text-green-600 mt-2">Gratis</div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ $activity->name }}
                            </h3>
                            
                            <div class="text-gray-600 text-sm space-y-1 mb-3">
                                <div>📍 {{ $activity->location }}</div>
                                
                                @php
                                    $totalParticipants = $activity->users->count() + $activity->externals->count();
                                @endphp
                                
                                <div>👥 {{ $totalParticipants }} deelnemers
                                    @if($activity->max_participants)
                                        (max {{ $activity->max_participants }})
                                    @endif
                                </div>
                                
                                @if($activity->includes_food)
                                    <div>🍕 Eten inbegrepen</div>
                                @endif
                            </div>

                            <p class="text-gray-700 text-sm leading-relaxed mb-4">
                                {{ $activity->description }}
                            </p>
                        </div>

                        <div class="md:w-32 flex-shrink-0 flex md:flex-col gap-2">
                            @php $isFull = $activity->max_participants && $totalParticipants >= $activity->max_participants; @endphp
                            
                            <button 
                                class="px-4 py-2 text-sm font-medium border transition-colors {{ $isFull ? 'border-gray-300 text-gray-500 cursor-not-allowed' : 'border-blue-600 text-blue-600 hover:bg-blue-50' }}"
                                {{ $isFull ? 'disabled' : '' }}>
                                {{ $isFull ? 'Vol' : 'Deelnemen' }}
                            </button>
                        </div>

                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $activities->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Geen activiteiten gepland</h3>
            <p class="text-gray-600">Er zijn momenteel geen aankomende activiteiten.</p>
        </div>
    @endif
</div>

</body>
</html>