{{-- resources/views/activity/create.blade.php --}}
@php
    use Illuminate\Support\Facades\Storage;

    $prefill     = $prefill     ?? [];
    $tempImages  = $tempImages  ?? [];
    $hasOld      = session()->has('_old_input');

    $val = function ($field, $default = '') use ($prefill, $hasOld) {
        return $hasOld ? old($field, $default) : ($prefill[$field] ?? $default);
    };

    $fmtDT = function ($value) {
        if (!$value) return '';
        try { return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d\TH:i'); }
        catch (\Throwable $e) { return ''; }
    };

    // Gebruik zelfde URL-patroon als je eerdere werkende view: asset('storage/'.$path)
    $initialTempPaths = $hasOld ? (array) old('temp_images', []) : (array) $tempImages;
    $initialTemp = collect($initialTempPaths)->map(fn($p) => [
        'path' => $p,
        'url'  => asset('storage/'.$p),
    ]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activiteit aanmaken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded border border-red-200 bg-red-50 text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data" id="activityCreateForm">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium" for="name">Naam</label>
                        <input class="w-full border rounded p-2" type="text" name="name" id="name" value="{{ $val('name') }}" required>
                        @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="location">Locatie</label>
                        <input class="w-full border rounded p-2" type="text" name="location" id="location" value="{{ $val('location') }}" required>
                        @error('location') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <input type="hidden" name="includes_food" value="0">
                        <label class="inline-flex items-center gap-2">
                            <input class="w-4 h-4" type="checkbox" name="includes_food" id="includes_food" value="1"
                                   {{ (bool)$val('includes_food', false) ? 'checked' : '' }}>
                            <span class="font-medium">Inclusief eten</span>
                        </label>
                        @error('includes_food') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="description">Beschrijving</label>
                        <textarea class="w-full border rounded p-2" name="description" id="description" required>{{ $val('description') }}</textarea>
                        @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="start_time">Starttijd</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="start_time" id="start_time" value="{{ $fmtDT($val('start_time')) }}" required>
                        @error('start_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="end_time">Eindtijd</label>
                        <input class="w-full border rounded p-2" type="datetime-local" name="end_time" id="end_time" value="{{ $fmtDT($val('end_time')) }}" required>
                        @error('end_time') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="cost">Kosten (€)</label>
                        <input class="w-full border rounded p-2" type="number" step="0.01" name="cost" id="cost" value="{{ $val('cost') }}" required>
                        @error('cost') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="max_participants">Maximaal aantal deelnemers</label>
                        <input class="w-full border rounded p-2" type="number" name="max_participants" id="max_participants" value="{{ $val('max_participants') }}">
                        @error('max_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium" for="min_participants">Minimaal aantal deelnemers</label>
                        <input class="w-full border rounded p-2" type="number" name="min_participants" id="min_participants" value="{{ $val('min_participants') }}">
                        @error('min_participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Afbeeldingen: temp uploader + restore --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-2" for="images_input">Afbeeldingen</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center" id="upload-area">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="mt-4">
                                <label for="images_input" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Sleep hierheen of <span class="text-blue-600 underline">klik om te selecteren</span>
                                    </span>
                                    <input type="file" id="images_input" class="sr-only" multiple accept="image/*">
                                </label>
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, WEBP tot 2MB per afbeelding</p>
                            </div>
                        </div>

                        <div id="image-previews" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 {{ $initialTemp->isEmpty() ? 'hidden' : '' }}"></div>

                        <div id="temp-images-container">
                            @foreach($initialTemp as $t)
                                <input type="hidden" name="temp_images[]" value="{{ $t['path'] }}">
                            @endforeach
                        </div>

                        @error('temp_images.*') <div class="text-red-500 text-sm mt-2">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Activiteit aanmaken
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const uploadArea = document.getElementById('upload-area');
            const fileInput  = document.getElementById('images_input');
            const preview    = document.getElementById('image-previews');
            const hiddenWrap = document.getElementById('temp-images-container');

            const STORAGE_BASE = @json(asset('storage'));

            let tempFiles = @json($initialTemp->values());

            function urlFor(item) {
                // Gebruik server-URL als die bestaat, anders /storage/{path}
                return item.url || (STORAGE_BASE + '/' + item.path);
            }

            function renderPreviews() {
                preview.innerHTML = '';
                if (!tempFiles.length) { preview.classList.add('hidden'); return; }
                preview.classList.remove('hidden');

                tempFiles.forEach(item => {
                    const imgSrc = item.previewUrl || urlFor(item);
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${imgSrc}" alt="Preview" class="w-full h-24 object-cover rounded border" onerror="this.closest('.group').querySelector('.fallback')?.classList.remove('hidden')">
                        <button type="button"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">×</button>
                        <div class="fallback hidden text-xs text-gray-600 mt-1 truncate">${(item.path || '').split('/').pop()}</div>
                    `;
                    div.querySelector('button').addEventListener('click', () => removeTemp(item.path));
                    preview.appendChild(div);
                });
            }

            function addHidden(path) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'temp_images[]';
                input.value = path;
                hiddenWrap.appendChild(input);
            }

            function removeTemp(path) {
                tempFiles = tempFiles.filter(t => t.path !== path);
                [...hiddenWrap.querySelectorAll('input[name="temp_images[]"]')]
                    .find(i => i.value === path)?.remove();
                renderPreviews();
            }

            async function uploadOne(file) {
                const fd = new FormData();
                fd.append('image', file);
                fd.append('_token', '{{ csrf_token() }}');
                try {
                    const r = await fetch('{{ route('uploads.temp') }}', {
                        method:'POST', headers:{'Accept':'application/json'}, body:fd
                    });
                    if (!r.ok) return null;
                    const data = await r.json();
                    return {
                        path: data.path,
                        url:  data.url || (STORAGE_BASE + '/' + data.path)
                    };
                } catch { return null; }
            }

            async function uploadFiles(files) {
                for (const file of files) {
                    const localUrl = URL.createObjectURL(file);
                    const res = await uploadOne(file);
                    if (!res) continue;
                    const item = { path: res.path, url: res.url, previewUrl: localUrl };
                    if (!tempFiles.some(t => t.path === item.path)) {
                        tempFiles.push(item);
                        addHidden(item.path);
                    }
                }
                renderPreviews();
            }

            uploadArea.addEventListener('dragover', e => {
                e.preventDefault();
                uploadArea.classList.add('bg-blue-50','border-blue-300');
            });
            uploadArea.addEventListener('dragleave', e => {
                e.preventDefault();
                uploadArea.classList.remove('bg-blue-50','border-blue-300');
            });
            uploadArea.addEventListener('drop', e => {
                e.preventDefault();
                uploadArea.classList.remove('bg-blue-50','border-blue-300');
                const files = [...e.dataTransfer.files].filter(f => f.type.startsWith('image/'));
                if (files.length) uploadFiles(files);
            });
            fileInput.addEventListener('change', e => {
                const files = [...e.target.files];
                if (files.length) uploadFiles(files);
                fileInput.value = '';
            });

            renderPreviews();
        });
    </script>
</x-app-layout>
