@extends('layouts.app')

@section('title', 'Menu Books')

@section('content')
<div class="container mt-3">
    <h1>Hello {{ Auth::user()->name }} ({{ Auth::user()->role }})</h1>
    <h4>List akses anda:</h4>

    <ul>
        @if(Auth::user()->role === 'librarian')
            <li>Categories</li>
            <li>Books</li>
            <li>Loans</li>
            <li>Users</li>
        @elseif(Auth::user()->role === 'member')
            <li>Loans</li>
        @endif
    </ul>
</div>
@endsection

@push('scripts')
<!-- Additional JS if needed -->
@endpush