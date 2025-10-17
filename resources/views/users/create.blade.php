<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gebruiker aanmaken
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- overflow-visible so dropdowns aren't cut off --}}
            <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Alerts --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <strong class="font-bold">Er zijn fouten opgetreden:</strong>
                            <ul class="mt-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="mb-4">Maak een gebruiker aan.</p>

                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" id="userForm">
                        @csrf
                        {{-- Voornaam --}}
                        <div>
                            <x-input-label for="first_name" :value="__('Voornaam')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        {{-- Achternaam --}}
                        <div class="mt-4">
                            <x-input-label for="last_name" :value="__('Achternaam')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        {{-- E-mailadres --}}
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('E-mailadres')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Functietitel (self-managing dropdown) --}}
                        <div class="mt-4">
                            <x-input-label for="job_title" :value="__('Functietitel')" />

                            <input type="hidden" id="job_title" name="job_title" value="{{ old('job_title') }}">

                            <div class="relative" id="jobTitleDropdown">
                                <button type="button"
                                        id="jobTitleToggle"
                                        class="mt-1 w-full inline-flex items-center justify-between border border-gray-300 rounded-md px-3 py-2 text-left bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                        aria-haspopup="listbox"
                                        aria-expanded="false">
                                    <span class="flex items-center gap-2">
                                        <span id="jobTitleSelectedText" class="text-gray-700">
                                            {{ old('job_title') ? e(old('job_title')) : 'Kies een functietitel…' }}
                                        </span>
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Menu --}}
                                <div id="jobTitleMenu"
                                     class="absolute z-[9999] hidden mt-1 w-full bg-white/95 backdrop-blur border border-gray-200 rounded-lg shadow-2xl">
                                    {{-- Search --}}
                                    <div class="p-2 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur rounded-t-lg">
                                        <div class="relative">
                                            <input type="text" id="jobTitleSearch"
                                                   class="w-full border border-gray-200 rounded-md pl-9 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Zoek functietitel…">
                                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19 15.5 14zM5 9.5C5 7.01 7.01 5 9.5 5S14 7.01 14 9.5 11.99 14 9.5 14 5 11.99 5 9.5z"/></svg>
                                        </div>
                                    </div>

                                    {{-- List --}}
                                    <ul id="jobTitleList" class="max-h-64 overflow-auto py-1">
                                        {{-- items injected by JS --}}
                                    </ul>

                                    {{-- Add new (independent) --}}
                                    <div class="p-2 border-t border-gray-100 bg-white/90 backdrop-blur rounded-b-lg">
                                        <div class="flex gap-2">
                                            <input type="text" id="jobTitleNewInput"
                                                   class="flex-1 border border-gray-200 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   placeholder="Nieuwe functietitel…">
                                            <button type="button"
                                                    id="jobTitleAddBtn"
                                                    class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 active:bg-blue-800 transition">
                                                Toevoegen
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-input-error :messages="$errors->get('job_title')" class="mt-2" />
                        </div>

                        {{-- Admin checkbox --}}
                        <div class="mt-4">
                            <div class="flex items-center">
                                <input id="is_admin"
                                    type="checkbox"
                                    name="is_admin"
                                    value="1"
                                    {{ old('is_admin') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <label for="is_admin" class="ml-2 text-sm font-medium text-gray-900">
                                    Gebruiker is beheerder
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />
                        </div>

                        {{-- Submit --}}
                        <div class="mt-6">
                            <x-primary-button id="submitBtn" class="inline-flex items-center">
                                <span id="buttonText">{{ __('Aanmaken') }}</span>
                                <svg id="loadingSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Disable button on submit --}}
    <script>
        document.getElementById('userForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('buttonText');
            const spinner = document.getElementById('loadingSpinner');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            text.textContent = 'Bezig met aanmaken...';
            spinner.classList.remove('hidden');
        });
    </script>

    {{-- Dropdown script (with Add / Edit / Delete) --}}
    <script>
    (function() {
        const dropdown   = document.getElementById('jobTitleDropdown');
        const toggleBtn  = document.getElementById('jobTitleToggle');
        const menu       = document.getElementById('jobTitleMenu');
        const listEl     = document.getElementById('jobTitleList');
        const searchEl   = document.getElementById('jobTitleSearch');
        const addBtn     = document.getElementById('jobTitleAddBtn');
        const addInput   = document.getElementById('jobTitleNewInput');
        const hidden     = document.getElementById('job_title');
        const selectedTx = document.getElementById('jobTitleSelectedText');
        const csrf = '{{ csrf_token() }}';
        let titles = [];
        let loaded = false;
        let editingId = null; // which item is being edited

        function openMenu() {
            menu.classList.remove('hidden');
            toggleBtn.setAttribute('aria-expanded', 'true');
            if (!loaded) {
                loadTitles().then(() => {
                    renderFiltered();
                    loaded = true;
                    searchEl.focus();
                });
            } else {
                renderFiltered();
                searchEl.focus();
            }
        }
        function closeMenu() {
            menu.classList.add('hidden');
            toggleBtn.setAttribute('aria-expanded', 'false');
            cancelEdit();
        }
        function toggleMenu() {
            if (menu.classList.contains('hidden')) openMenu(); else closeMenu();
        }

        async function loadTitles(q = '') {
            const url = new URL('{{ route('job-titles.index') }}', window.location.origin);
            if (q) url.searchParams.set('q', q);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
            titles = await res.json();
        }

        function renderList(items) {
    listEl.innerHTML = '';
    if (!items.length) {
        listEl.innerHTML = '<li class="px-4 py-3 text-sm text-gray-500">Geen resultaten</li>';
        return;
    }

    items.forEach(t => {
        const isSelected = hidden.value && hidden.value === t.name;
        const isEditing  = editingId === t.id;

        const li = document.createElement('li');
        li.className = 'group px-2';

        // Make the whole row a button for easy clicking
        const rowBtn = document.createElement('button');
        rowBtn.type = 'button';
        rowBtn.className = 'w-full flex items-center gap-2 px-2 py-2 rounded-md hover:bg-gray-50 transition text-left';
        rowBtn.setAttribute('aria-pressed', isSelected ? 'true' : 'false');

        // LEFT: checkbox + label OR edit input
        const left = document.createElement('div');
        left.className = 'flex items-center gap-2 flex-1 min-w-0';

        if (!isEditing) {
            // Real checkbox that mirrors selection
            const box = document.createElement('input');
            box.type = 'checkbox';
            box.checked = isSelected;
            box.className = 'w-4 h-4 accent-blue-600 cursor-pointer';
            // clicking checkbox should select this title
            box.addEventListener('click', (e) => {
                e.stopPropagation();
                selectTitle(t.name);
            });

            const label = document.createElement('span');
            label.className = 'truncate';
            label.textContent = t.name;

            left.appendChild(box);
            left.appendChild(label);

            // Clicking anywhere on the row selects
            rowBtn.addEventListener('click', () => selectTitle(t.name));
            rowBtn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectTitle(t.name);
                }
            });
        } else {
            const editInput = document.createElement('input');
            editInput.type = 'text';
            editInput.value = t.name;
            editInput.className = 'flex-1 border border-gray-300 rounded-md px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            editInput.addEventListener('keydown', (e) => {
                e.stopPropagation();
                if (e.key === 'Enter') saveEdit(t.id, editInput.value.trim());
                if (e.key === 'Escape') cancelEdit();
            });
            left.appendChild(editInput);
            setTimeout(() => editInput.focus(), 0);
        }

        // RIGHT: actions
        const right = document.createElement('div');
        right.className = 'flex items-center gap-1';

        if (!isEditing) {
            const editBtn = document.createElement('button');
            editBtn.type = 'button';
            editBtn.className = 'p-2 rounded hover:bg-gray-100';
            editBtn.title = 'Bewerken';
            editBtn.innerHTML = '<svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="currentColor"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 000-1.42l-2.34-2.34a1.003 1.003 0 00-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z"/></svg>';
            editBtn.addEventListener('click', (e) => { e.stopPropagation(); startEdit(t.id); });

            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'p-2 rounded hover:bg-red-50';
            delBtn.title = 'Verwijderen';
            delBtn.innerHTML = '<svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 3H8l1-3z"/></svg>';
            delBtn.addEventListener('click', (e) => { e.stopPropagation(); confirmDelete(t.id, t.name); });

            right.appendChild(editBtn);
            right.appendChild(delBtn);
        } else {
            const saveBtn = document.createElement('button');
            saveBtn.type = 'button';
            saveBtn.className = 'p-2 rounded bg-green-600 hover:bg-green-700 text-white';
            saveBtn.title = 'Opslaan';
            saveBtn.innerHTML = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2l-3.5-3.5-1.4 1.4L9 19 20.3 7.7l-1.4-1.4z"/></svg>';
            saveBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const newName = rowBtn.querySelector('input[type="text"]')?.value.trim() || '';
                saveEdit(t.id, newName);
            });

            const cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.className = 'p-2 rounded hover:bg-gray-100';
            cancelBtn.title = 'Annuleren';
            cancelBtn.innerHTML = '<svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>';
            cancelBtn.addEventListener('click', (e) => { e.stopPropagation(); cancelEdit(); });

            right.appendChild(saveBtn);
            right.appendChild(cancelBtn);
        }

        rowBtn.appendChild(left);
        rowBtn.appendChild(right);
        li.appendChild(rowBtn);
        listEl.appendChild(li);
    });
}


        function renderFiltered() {
            const q = (searchEl.value || '').toLowerCase();
            const filtered = titles.filter(t => t.name.toLowerCase().includes(q));
            renderList(filtered);
        }

        function selectTitle(name) {
            hidden.value = name;
            selectedTx.textContent = name;
            closeMenu();
        }

        function startEdit(id) { editingId = id; renderFiltered(); }
        function cancelEdit()  { editingId = null; renderFiltered(); }

        async function addTitle(name) {
            if (!name) return;
            const res = await fetch('{{ route('job-titles.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ name })
            });
            if (res.ok) {
                const created = await res.json();
                titles.push(created);
                searchEl.value = '';
                renderFiltered();
                selectTitle(created.name);
                addInput.value = '';
            } else {
                const data = await res.json().catch(() => ({}));
                alert(data?.message ?? 'Kon functietitel niet toevoegen (bestaat mogelijk al).');
            }
        }

        async function saveEdit(id, newName) {
            if (!newName) { alert('Naam mag niet leeg zijn.'); return; }
            const url = '{{ url('/job-titles') }}/' + id;
            const res = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ name: newName })
            });
            if (res.ok) {
                const updated = await res.json();
                titles = titles.map(t => t.id === id ? updated : t);
                if (hidden.value === updated.old_name) {
                    // if the selected name changed, update visible selection
                    hidden.value = updated.name;
                    selectedTx.textContent = updated.name;
                }
                cancelEdit();
            } else {
                const data = await res.json().catch(() => ({}));
                alert(data?.message ?? 'Kon functietitel niet bijwerken (naam bestaat mogelijk al).');
            }
        }

        async function deleteTitle(id) {
            const url = '{{ url('/job-titles') }}/' + id;
            const res = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                }
            });
            if (res.ok) {
                const removed = titles.find(t => t.id === id);
                titles = titles.filter(t => t.id !== id);
                // if deleting the currently selected title, keep the hidden value but you may clear it:
                if (removed && hidden.value === removed.name) {
                    hidden.value = '';
                    selectedTx.textContent = 'Kies een functietitel…';
                }
                renderFiltered();
            } else {
                alert('Kon functietitel niet verwijderen.');
            }
        }

        function confirmDelete(id, name) {
            if (confirm(`Verwijder functietitel: "${name}"?`)) {
                deleteTitle(id);
            }
        }

        // Events
        toggleBtn.addEventListener('click', toggleMenu);
        searchEl.addEventListener('input', renderFiltered);
        addBtn.addEventListener('click', () => addTitle((addInput.value || '').trim()));
        addInput.addEventListener('keydown', (e) => {
            // prevent outer form submit on Enter
            if (e.key === 'Enter') { e.preventDefault(); addBtn.click(); }
            e.stopPropagation();
        });

        // click-away
        document.addEventListener('click', e => {
            if (!dropdown.contains(e.target)) closeMenu();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMenu();
        });
    })();
    </script>
</x-app-layout>
