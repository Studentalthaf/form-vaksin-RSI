@extends('layouts.app')

@section('title', 'Login - Form Vaksin')

@section('content')
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md">
        <!-- Card Login -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Form Vaksin</h1>
                <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Alert Success -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Alert Error -->
            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700 text-sm font-semibold mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Login -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('email') border-red-500 @enderror"
                        placeholder="admin@example.com"
                        required
                        autofocus
                    >
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Masuk
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    <strong>Demo Login:</strong><br>
                    Admin: admin@hospital.com / password123<br>
                    Dokter: dokter@hospital.com / password123
                </p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} Form Vaksin. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection
