@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="flex items-center justify-center min-h-screen px-4 bg-gray-50">
    <div class="text-center">
        <div class="mb-8">
            <svg class="w-24 h-24 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        
        <h1 class="text-6xl font-bold text-gray-800 mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Akses Ditolak</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>
        
        <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
