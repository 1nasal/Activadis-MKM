<x-app-layout>
  <div class="max-w-6xl mx-auto py-8 px-4">
    <header>
      <h1 class="text-2xl font-bold text-gray-800 mb-3">Aankomende Activiteiten</h1>
    </header>

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="mb-4">
      <form method="GET" action="{{ url()->current() }}" class="relative">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Zoek op titel, beschrijving of locatie..." class="w-full px-4 py-3 pl-11 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">⌕</span>
        @if(request('search'))
          <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg leading-none">×</a>
        @endif
        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
        @if(request('order')) <input type="hidden" name="order" value="{{ request('order') }}"> @endif
      </form>
    </div>

    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="relative">
          <button id="sortDropdownButton" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-md">
            <span class="mr-2">⇅</span>
            <span id="sortButtonText">
              @if($sortBy === 'start_time' && $sortOrder === 'asc') Datum (vroeg → laat)
              @elseif($sortBy === 'start_time' && $sortOrder === 'desc') Datum (laat → vroeg)
              @elseif($sortBy === 'name' && $sortOrder === 'asc') Naam (A → Z)
              @elseif($sortBy === 'name' && $sortOrder === 'desc') Naam (Z → A)
              @elseif($sortBy === 'participants' && $sortOrder === 'asc') Deelnemers (weinig → veel)
              @elseif($sortBy === 'participants' && $sortOrder === 'desc') Deelnemers (veel → weinig)
              @else Sorteer op @endif
            </span>
            <span class="ml-2">▾</span>
          </button>

          <div id="sortDropdownMenu" class="hidden absolute left-0 mt-1 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-10">
            <div class="py-1">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'start_time' && $sortOrder === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">Datum (vroeg → laat)</a>
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_time', 'order' => 'desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'start_time' && $sortOrder === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">Datum (laat → vroeg)</a>
              <hr class="my-1">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'name' && $sortOrder === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">Naam (A → Z)</a>
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'name' && $sortOrder === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">Naam (Z → A)</a>
              <hr class="my-1">
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'participants', 'order' => 'asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'participants' && $sortOrder === 'asc' ? 'bg-blue-50 text-blue-700' : '' }}">Deelnemers (weinig → veel)</a>
              <a href="{{ request()->fullUrlWithQuery(['sort' => 'participants', 'order' => 'desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $sortBy === 'participants' && $sortOrder === 'desc' ? 'bg-blue-50 text-blue-700' : '' }}">Deelnemers (veel → weinig)</a>
            </div>
          </div>
        </div>

        <span class="text-sm text-gray-600">
          {{ $activities->total() }} activiteit{{ $activities->total() !== 1 ? 'en' : '' }}
          @if(request('search')) <span class="text-gray-500">voor "{{ request('search') }}"</span> @endif
        </span>
      </div>

      @if(request()->has('sort') || request()->has('order') || request()->has('search'))
        <a href="{{ url()->current() }}" class="text-sm text-blue-600 hover:text-blue-800 underline">Reset filters</a>
      @endif
    </div>

    @if($activities->count() > 0)
      <div class="space-y-4">
        @foreach($activities as $activity)
          @php
            $confirmedExternalsCount = $activity->externals()->wherePivot('confirmed', true)->count();
            $totalParticipants = $activity->users->count() + $confirmedExternalsCount;
            $isFull = $activity->max_participants && $totalParticipants >= $activity->max_participants;
            $isEnrolled = auth()->check() && $activity->users->contains(auth()->id());
          @endphp

          <article class="bg-white border border-gray-200 p-6 hover:border-gray-300 transition-colors cursor-pointer rounded-xl" onclick="openActivityModal({{ $activity->id }})">
            <div class="flex flex-col md:flex-row gap-6">
              <div class="md:w-64 flex-shrink-0 relative">
                @if($activity->images->count() > 1)
                  <div class="relative group">
                    <div class="image-carousel" id="carousel-{{ $activity->id }}">
                      @foreach($activity->images as $index => $image)
                        <img src="{{ $image->url }}" alt="{{ $image->original_name }}" class="w-full h-48 object-cover rounded-lg border carousel-image {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                      @endforeach
                    </div>
                    <button onclick="event.stopPropagation(); previousImage({{ $activity->id }})" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/55 text-white rounded-full w-10 h-10 flex items-center justify-center leading-[0] opacity-0 group-hover:opacity-100 transition"><span class="-translate-x-[1px] text-2xl select-none pointer-events-none">‹</span></button>
                    <button onclick="event.stopPropagation(); nextImage({{ $activity->id }})" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/55 text-white rounded-full w-10 h-10 flex items-center justify-center leading-[0] opacity-0 group-hover:opacity-100 transition"><span class="translate-x-[1px] text-2xl select-none pointer-events-none">›</span></button>
                    <div class="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition"><span id="counter-{{ $activity->id }}">1</span>/{{ $activity->images->count() }}</div>
                  </div>
                @elseif($activity->images->count() === 1)
                  <img src="{{ $activity->images->first()->url }}" alt="{{ $activity->images->first()->original_name }}" class="w-full h-48 object-cover rounded-lg border">
                @else
                  <img src="{{ $activity->primary_image_url }}" alt="{{ $activity->name }}" class="w-full h-48 object-cover rounded-lg border">
                @endif
              </div>

              <div class="md:w-40 flex-shrink-0">
                <div class="text-sm text-gray-500 mb-1">{{ $activity->start_time->format('j M') }}</div>
                <div class="text-lg font-medium text-gray-900">{{ $activity->start_time->format('H:i') }}</div>
                @if($activity->end_time)
                  <div class="text-sm text-gray-600 mt-1">tot {{ $activity->end_time->format('H:i') }}</div>
                @endif
                @if($activity->cost > 0)
                  <div class="text-sm font-medium text-blue-600 mt-2">€{{ number_format($activity->cost, 2) }}</div>
                @else
                  <div class="text-sm font-medium text-green-600 mt-2">Gratis</div>
                @endif
              </div>

              <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $activity->name }}</h3>
                <div class="text-gray-600 text-sm space-y-1 mb-3">
                  <div>Locatie: {{ $activity->location }}</div>
                  <div>
                    {{ $totalParticipants }} deelnemers
                    @if($activity->min_participants || $activity->max_participants)
                      (
                        @if($activity->min_participants) min {{ $activity->min_participants }}@endif
                        @if($activity->min_participants && $activity->max_participants), @endif
                        @if($activity->max_participants) max {{ $activity->max_participants }}@endif
                      )
                    @endif
                  </div>
                  @if($activity->includes_food)<div>Eten inbegrepen</div>@endif
                </div>
                <p class="text-gray-700 text-sm leading-relaxed mb-4">
                  @php
                    $maxLength = 150;
                    $description = $activity->description;
                    if (strlen($description) > $maxLength) {
                        $description = substr($description, 0, $maxLength);
                        $description = substr($description, 0, strrpos($description, ' ')) . '...';
                    }
                  @endphp
                  {{ $description }}
                </p>
              </div>

              <div class="md:w-32 flex-shrink-0 flex md:flex-col gap-2">
                @if($isEnrolled)
                  <button onclick="event.stopPropagation(); openActivityModal({{ $activity->id }})" class="px-4 py-2 text-sm font-medium border border-green-600 text-green-600 hover:bg-green-50 transition">Ingeschreven</button>
                @else
                  <button id="join-btn-{{ $activity->id }}" onclick="event.stopPropagation(); @auth joinActivityDirectly({{ $activity->id }}) @else openParticipantModal({{ $activity->id }}) @endauth" class="px-4 py-2 text-sm font-medium border transition {{ $isFull ? 'border-gray-300 text-gray-500 cursor-not-allowed' : 'border-blue-600 text-blue-600 hover:bg-blue-50' }}" {{ $isFull ? 'disabled' : '' }}>
                    <span class="join-btn-text">{{ $isFull ? 'Vol' : 'Inschrijven' }}</span>
                    <span class="join-btn-spinner hidden animate-spin h-4 w-4 mx-auto">⟳</span>
                  </button>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <div class="mt-8 flex justify-center">{{ $activities->links() }}</div>
    @else
      <div class="text-center py-16">
        <div class="text-gray-400 mb-4">
          <span class="w-16 h-16 mx-auto text-6xl leading-none">📅</span>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Geen activiteiten gevonden</h3>
        <p class="text-gray-600">
          @if(request('search')) Er zijn geen activiteiten die voldoen aan de zoekopdracht "{{ request('search') }}".
          @else Er zijn momenteel geen aankomende activiteiten met de huidige filters. @endif
        </p>
        @if(request()->has('sort') || request()->has('order') || request()->has('search'))
          <a href="{{ url()->current() }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 underline">Reset filters om alle activiteiten te bekijken</a>
        @endif
      </div>
    @endif
  </div>

  <div id="activityModal" class="fixed inset-0 z-50 hidden flex items-start justify-center p-4 pt-20">
    <div id="activityModalOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="relative mx-auto max-w-4xl w-full">
      <div class="bg-white rounded-lg shadow-xl ring-1 ring-black/5 w-full max-h-[calc(100vh-6rem)] overflow-y-auto flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <div class="min-w-0">
            <h3 id="modalActivityTitle" class="text-xl font-semibold text-gray-900 truncate"></h3>
            <p id="modalActivityMeta" class="mt-0.5 text-sm text-gray-500"></p>
          </div>
          <button onclick="closeActivityModal()" class="px-2 py-1 rounded-full hover:bg-gray-100 transition text-2xl leading-none" aria-label="Sluiten">×</button>
        </div>

        <div class="flex-1 overflow-y-auto" id="modalScrollable">
          <div class="p-6 space-y-8">
            <div id="modalGallery" class="relative rounded-xl overflow-hidden bg-gray-50">
              <div id="modal-carousel-track" class="relative w-full flex items-center justify-center h-56 md:h-64"></div>
              <button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/55 text-white rounded-full w-10 h-10 flex items-center justify-center leading-[0]" onclick="modalCarouselPrev()" aria-label="Vorige"><span class="-translate-x-[1px] text-2xl select-none pointer-events-none">‹</span></button>
              <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/55 text-white rounded-full w-10 h-10 flex items-center justify-center leading-[0]" onclick="modalCarouselNext()" aria-label="Volgende"><span class="translate-x-[1px] text-2xl select-none pointer-events-none">›</span></button>
              <div id="modal-carousel-dots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
              <div class="md:col-span-2 space-y-6">
                <div id="modalBadges" class="flex flex-wrap gap-2"></div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900">Beschrijving</h4>
                  <p id="modalDescription" class="mt-2 text-gray-700 leading-relaxed"></p>
                </div>
                <div id="modalRequirementsWrap" class="hidden">
                  <h4 class="text-lg font-semibold text-gray-900">Vereisten</h4>
                  <p id="modalRequirements" class="mt-2 text-gray-700 leading-relaxed"></p>
                </div>
              </div>

              <aside class="space-y-4">
                <div class="rounded-xl border p-4 space-y-4">
                  <div>
                    <p class="text-xs text-gray-500">Datum</p>
                    <p id="modalDate" class="font-medium text-gray-900"></p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500">Tijden</p>
                    <p id="modalTimes" class="font-medium text-gray-900"></p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500">Locatie</p>
                    <p id="modalLocation" class="font-medium text-gray-900"></p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500">Kosten</p>
                    <p id="modalCost" class="font-medium text-gray-900"></p>
                  </div>
                  <div id="modalCapacityWrap">
                    <p class="text-xs text-gray-500">Deelnemers</p>
                    <p id="modalParticipants" class="font-medium text-gray-900"></p>
                  </div>
                </div>
              </aside>
            </div>
          </div>
        </div>

        <div class="sticky bottom-0 border-t bg-white/95 backdrop-blur px-6 py-4 flex items-center gap-3">
          <button id="modalJoinButton" onclick="openParticipantModalFromDetail()" type="button" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition inline-flex items-center">
            <span id="modalJoinButtonText">Inschrijven</span>
            <span id="modalJoinSpinner" class="hidden animate-spin ml-2 h-5 w-5">⟳</span>
          </button>
          <button onclick="closeActivityModal()" class="px-5 py-2.5 rounded-lg border hover:bg-gray-50 transition">Sluiten</button>
        </div>
      </div>
    </div>
  </div>

  <!-- VOLLEDIG SCHERM AFBEELDING -->
  <div id="imageModal" class="fixed inset-0 z-[70] hidden">
    <div id="imageModalOverlay" class="absolute inset-0 bg-black/75"></div>

    <button data-no-close class="fixed top-4 right-4 md:top-6 md:right-6 bg-black text-white rounded-full w-11 h-11 flex items-center justify-center shadow-lg border border-white/20 z-[90] text-2xl leading-[0]" onclick="closeImageModal()" aria-label="Sluiten">×</button>

    <div id="imageModalStage" class="relative z-[80] flex items-center justify-center w-full h-full pt-16 md:pt-20 px-4">
      <img id="modalImage" src="" alt="" class="max-w-full w-auto h-auto object-contain rounded-lg shadow max-h-[calc(100vh-10rem)] md:max-h-[calc(100vh-12rem)]" onclick="event.stopPropagation()">
    </div>
  </div>

  <div id="participantModal" class="fixed inset-0 bg-black/50 hidden z-60 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
      <div class="p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Inschrijven voor activiteit</h3>
          <button onclick="closeParticipantModal()" class="text-gray-400 hover:text-gray-600 transition text-xl leading-none" aria-label="Sluiten">×</button>
        </div>
        <p class="text-gray-600 mb-6">Vul uw gegevens in.</p>
        <form id="participantForm" method="POST" action="">
          @csrf
          <div class="space-y-3">
            <div>
              <label for="first_name" class="block text-sm font-medium text-gray-700">Voornaam</label>
              <input type="text" id="first_name" name="first_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label for="last_name" class="block text-sm font-medium text-gray-700">Achternaam</label>
              <input type="text" id="last_name" name="last_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" id="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" id="participantFormSubmit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center justify-center">
              <span id="participantFormText">inschrijven</span>
              <span id="participantFormSpinner" class="hidden animate-spin ml-2 h-5 w-5">⟳</span>
            </button>
          </div>
        </form>
        <button onclick="closeParticipantModal()" class="w-full mt-4 px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">Annuleren</button>
      </div>
    </div>
  </div>

  <script>
    let selectedActivityId = null
    let imageCounters = {}
    let activitiesData = {}

    @foreach($activities as $activity)
      activitiesData[{{ $activity->id }}] = {
        id: {{ $activity->id }},
        name: @json($activity->name),
        description: @json($activity->description),
        requirements: @json($activity->requirements),
        location: @json($activity->location),
        start_time: @json($activity->start_time->format('d-m-Y H:i')),
        end_time: @json($activity->end_time ? $activity->end_time->format('d-m-Y H:i') : null),
        cost: {{ $activity->cost }},
        includes_food: {{ $activity->includes_food ? 'true' : 'false' }},
        max_participants: {{ $activity->max_participants ?? 'null' }},
        min_participants: {{ $activity->min_participants ?? 'null' }},
        images: [
          @foreach($activity->images as $image)
            { url: @json($image->url), name: @json($image->original_name) },
          @endforeach
        ],
        primary_image_url: @json($activity->primary_image_url),
        total_participants: {{ $activity->users->count() + $activity->externals->where('confirmed', true)->count() }},
        is_enrolled: {{ auth()->check() && $activity->users->contains(auth()->id()) ? 'true' : 'false' }}
      }
      @if($activity->images->count() > 1)
        imageCounters[{{ $activity->id }}] = 0
      @endif
    @endforeach

    window.joinActivityDirectly = function(activityId){
      const button = document.getElementById(`join-btn-${activityId}`)
      if(button){
        const textSpan = button.querySelector('.join-btn-text')
        const spinner = button.querySelector('.join-btn-spinner')
        button.disabled = true
        button.classList.add('opacity-75','cursor-not-allowed')
        if(textSpan) textSpan.classList.add('hidden')
        if(spinner) spinner.classList.remove('hidden')
      }
      const form = document.createElement('form')
      form.method = 'POST'
      form.action = `/activities/${activityId}/join`
      const csrf = document.createElement('input')
      csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}'
      form.appendChild(csrf)
      document.body.appendChild(form)
      form.submit()
    }

    window.nextImage = function(activityId){
      const carousel = document.getElementById(`carousel-${activityId}`)
      const images = carousel.querySelectorAll('.carousel-image')
      const counter = document.getElementById(`counter-${activityId}`)
      images[imageCounters[activityId]].classList.add('hidden')
      imageCounters[activityId] = (imageCounters[activityId] + 1) % images.length
      images[imageCounters[activityId]].classList.remove('hidden')
      counter.textContent = imageCounters[activityId] + 1
    }
    window.previousImage = function(activityId){
      const carousel = document.getElementById(`carousel-${activityId}`)
      const images = carousel.querySelectorAll('.carousel-image')
      const counter = document.getElementById(`counter-${activityId}`)
      images[imageCounters[activityId]].classList.add('hidden')
      imageCounters[activityId] = (imageCounters[activityId] - 1 + images.length) % images.length
      images[imageCounters[activityId]].classList.remove('hidden')
      counter.textContent = imageCounters[activityId] + 1
    }

    function euro(v){ return v>0 ? `€${Number(v).toFixed(2).replace('.', ',')}` : 'Gratis' }

    window.openActivityModal = function(activityId){
      const a = activitiesData[activityId]
      if(!a) return
      selectedActivityId = activityId

      document.getElementById('modalActivityTitle').textContent = a.name
      document.getElementById('modalActivityMeta').textContent = `${a.location}`
      document.getElementById('modalDate').textContent = a.start_time?.split(' ')[0] || ''
      document.getElementById('modalTimes').textContent = a.end_time ? `${a.start_time?.split(' ')[1]} – ${a.end_time.split(' ')[1]}` : `${a.start_time?.split(' ')[1]}`
      document.getElementById('modalLocation').textContent = a.location
      document.getElementById('modalCost').textContent = euro(a.cost)

      const details=[]
      if(a.min_participants) details.push(`min ${a.min_participants}`)
      if(a.max_participants) details.push(`max ${a.max_participants}`)
      document.getElementById('modalParticipants').textContent = `${a.total_participants ?? 0} ${a.total_participants==1?'deelnemer':'deelnemers'}${details.length?' ('+details.join(', ')+')':''}`
      document.getElementById('modalCapacityWrap').classList.toggle('hidden', a.total_participants==null)

      const badges = []
      if(a.includes_food) badges.push(`<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">Eten inbegrepen</span>`)
      badges.push(`<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium ${a.cost>0?'bg-amber-50 text-amber-700 ring-amber-200':'bg-emerald-50 text-emerald-700 ring-emerald-200'} ring-1">${euro(a.cost)}</span>`)
      if(a.is_enrolled) badges.push(`<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-200">Ingeschreven</span>`)
      document.getElementById('modalBadges').innerHTML = badges.join('')

      document.getElementById('modalDescription').textContent = a.description || ''
      const reqWrap = document.getElementById('modalRequirementsWrap')
      document.getElementById('modalRequirements').textContent = a.requirements || ''
      reqWrap.classList.toggle('hidden', !a.requirements)

      const track = document.getElementById('modal-carousel-track')
      const dots  = document.getElementById('modal-carousel-dots')
      const imgs = (a.images?.length? a.images : [{url: a.primary_image_url, name: a.name}])

      track.innerHTML = imgs.map((img,i)=>`
        <img
          src="${img.url}"
          alt="${img.name||a.name}"
          class="select-none max-h-56 md:max-h-64 object-contain transition-opacity duration-300 ease-out rounded-lg ${i===0?'opacity-100':'opacity-0 absolute'} cursor-pointer"
          style="${i===0?'position:relative;':'inset:0;'}"
          data-slide="${i}"
          onclick="openImageModal('${img.url.replace(/'/g, "\\'")}', '${(img.name||a.name).replace(/'/g, "\\'")}')"
        >
      `).join('')

      dots.innerHTML = imgs.map((_,i)=>`<button type="button" class="w-2.5 h-2.5 rounded-full ${i===0?'bg-white':'bg-white/50'} ring-1 ring-white/60" onclick="modalCarouselGoTo(${i})" aria-label="Ga naar afbeelding ${i+1}"></button>`).join('')

      initModalCarousel(imgs.length)

      const joinButton = document.getElementById('modalJoinButton')
      const joinText   = document.getElementById('modalJoinButtonText')
      const isFull = a.max_participants && Number(a.total_participants) >= Number(a.max_participants)
      if(a.is_enrolled){
        joinText.textContent = 'Uitschrijven'
        joinButton.className = 'px-5 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition'
        joinButton.disabled = false
        joinButton.onclick = function(e){ e.stopPropagation(); window.leaveActivityFromModal() }
      }else if(isFull){
        joinText.textContent = 'Vol'
        joinButton.className = 'px-5 py-2.5 rounded-lg bg-gray-400 text-white cursor-not-allowed'
        joinButton.disabled = true
        joinButton.onclick = null
      }else{
        joinText.textContent = 'Inschrijven'
        joinButton.className = 'px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition inline-flex items-center'
        joinButton.disabled = false
        joinButton.onclick = function(){ openParticipantModalFromDetail() }
      }

      document.getElementById('activityModal').classList.remove('hidden')
      document.body.style.overflow = 'hidden'
    }

    let __modalCarousel = { index: 0, total: 0, touchStartX: null }
    function initModalCarousel(total){
      __modalCarousel.index = 0
      __modalCarousel.total = total
      document.addEventListener('keydown', modalCarouselKeyHandler)
      const track = document.getElementById('modal-carousel-track')
      if(track){
        track.addEventListener('touchstart', e=>{ __modalCarousel.touchStartX = e.touches[0].clientX }, {passive:true})
        track.addEventListener('touchend', e=>{
          if(__modalCarousel.touchStartX===null) return
          const d = e.changedTouches[0].clientX - __modalCarousel.touchStartX
          __modalCarousel.touchStartX = null
          if(d>30) modalCarouselPrev()
          if(d<-30) modalCarouselNext()
        }, {passive:true})
      }
    }
    function modalCarouselKeyHandler(e){
      const m = document.getElementById('activityModal')
      if(!m || m.classList.contains('hidden')) return
      if(e.key==='ArrowRight') modalCarouselNext()
      if(e.key==='ArrowLeft') modalCarouselPrev()
      if(e.key==='Escape') closeActivityModal()
    }
    function modalCarouselGoTo(i){ if(__modalCarousel.total<=0) return; __modalCarousel.index=(i+__modalCarousel.total)%__modalCarousel.total; renderModalCarousel() }
    function modalCarouselNext(){ if(__modalCarousel.total<=0) return; __modalCarousel.index=(__modalCarousel.index+1)%__modalCarousel.total; renderModalCarousel() }
    function modalCarouselPrev(){ if(__modalCarousel.total<=0) return; __modalCarousel.index=(__modalCarousel.index-1+__modalCarousel.total)%__modalCarousel.total; renderModalCarousel() }
    function renderModalCarousel(){
      const slides = document.querySelectorAll('#modal-carousel-track [data-slide]')
      const dots   = document.querySelectorAll('#modal-carousel-dots > button')
      slides.forEach((el,i)=>{
        const active = i===__modalCarousel.index
        el.classList.toggle('opacity-100', active)
        el.classList.toggle('opacity-0', !active)
        el.style.position = active ? 'relative' : 'absolute'
      })
      dots.forEach((d,i)=>{ d.classList.toggle('bg-white', i===__modalCarousel.index); d.classList.toggle('bg-white/50', i!==__modalCarousel.index) })
    }

    window.leaveActivityFromModal = function(){
      if(!selectedActivityId) return
      if(!confirm('Weet je zeker dat je je wilt uitschrijven voor deze activiteit?')) return
      const form = document.createElement('form')
      form.method = 'POST'; form.action = `/activities/${selectedActivityId}/leave`
      const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='{{ csrf_token() }}'; form.appendChild(csrf)
      const method = document.createElement('input'); method.type='hidden'; method.name='_method'; method.value='DELETE'; form.appendChild(method)
      document.body.appendChild(form)
      form.submit()
    }

    window.closeActivityModal = function(){
      const modal = document.getElementById('activityModal')
      if(!modal) return
      document.removeEventListener('keydown', modalCarouselKeyHandler)
      modal.classList.add('hidden')
      document.body.style.overflow = 'auto'
    }

    window.openParticipantModal = function(activityId){
      selectedActivityId = activityId
      const form = document.getElementById('participantForm')
      form.action = `/activities/${activityId}/join`
      document.getElementById('participantModal').classList.remove('hidden')
      document.body.style.overflow = 'hidden'
    }

    window.openParticipantModalFromDetail = function(){
      if(!selectedActivityId) return
      @auth
        const modalButton = document.getElementById('modalJoinButton')
        const modalButtonText = document.getElementById('modalJoinButtonText')
        const modalSpinner = document.getElementById('modalJoinSpinner')
        modalButton.disabled = true
        modalButton.classList.add('opacity-75','cursor-not-allowed')
        modalButtonText.textContent = 'Bezig met inschrijven...'
        modalSpinner.classList.remove('hidden')
        joinActivityDirectly(selectedActivityId)
      @else
        openParticipantModal(selectedActivityId)
      @endauth
    }

    window.closeParticipantModal = function(){
      document.getElementById('participantModal').classList.add('hidden')
      document.body.style.overflow = 'auto'
    }

    window.openImageModal = function(url, name){
      const img = document.getElementById('modalImage')
      img.src = url
      img.alt = name || ''
      const wrap = document.getElementById('imageModal')
      wrap.classList.remove('hidden')
      document.body.style.overflow = 'hidden'
    }
    window.closeImageModal = function(){
      document.getElementById('imageModal').classList.add('hidden')
      document.body.style.overflow = 'auto'
      const img = document.getElementById('modalImage')
      img.src = ''
      img.alt = ''
    }

    document.addEventListener('DOMContentLoaded', function(){
      const sortButton = document.getElementById('sortDropdownButton')
      const sortMenu = document.getElementById('sortDropdownMenu')
      if(sortButton && sortMenu){
        sortButton.addEventListener('click', function(e){ e.stopPropagation(); sortMenu.classList.toggle('hidden') })
        document.addEventListener('click', function(e){ if(!sortButton.contains(e.target) && !sortMenu.contains(e.target)) sortMenu.classList.add('hidden') })
      }

      const activityOverlay = document.getElementById('activityModalOverlay')
      if(activityOverlay){ activityOverlay.addEventListener('click', closeActivityModal) }

      const imageOverlay = document.getElementById('imageModalOverlay')
      const imageStage = document.getElementById('imageModalStage')
      if(imageOverlay){ imageOverlay.addEventListener('click', closeImageModal) }
      if(imageStage){ imageStage.addEventListener('click', closeImageModal) }

      document.addEventListener('keydown', function(e){
        if(e.key==='Escape'){
          if(!document.getElementById('imageModal').classList.contains('hidden')) closeImageModal()
          else if(!document.getElementById('activityModal').classList.contains('hidden')) closeActivityModal()
          else if(!document.getElementById('participantModal').classList.contains('hidden')) closeParticipantModal()
          else{ const m = document.getElementById('sortDropdownMenu'); if(m) m.classList.add('hidden') }
        }
      })
    })
  </script>
</x-app-layout>
