<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Activeren') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            Welkom {{ $user->first_name }}!
                        </h3>
                        <p class="text-gray-600">
                            Stel hieronder je wachtwoord in om je account te activeren.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('activation.activate', $token) }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Wachtwoord <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                placeholder="Minimaal 8 tekens"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FAA21B] focus:border-[#FAA21B] @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Bevestig wachtwoord <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                placeholder="Herhaal je wachtwoord"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FAA21B] focus:border-[#FAA21B]"
                            >
                        </div>
                        <div class="pt-4">
                            <button 
                                type="submit" 
                                aria-label="Account activeren"
                                class="w-full px-6 py-3 bg-brand text-white font-medium rounded-lg shadow-md border border-brandBorder hover:bg-brandHover transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand focus:ring-offset-white"
                            >
                                Account activeren
                            </button>

                        </div>

                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500 text-center">
                            Heb je vragen? Neem contact op met je beheerder.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>