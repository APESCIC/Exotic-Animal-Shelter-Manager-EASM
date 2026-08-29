@extends('layouts.app')

@section('title', $animal->name.' · '.config('app.name'))

@section('content')
    <h1>{{ $animal->name }}</h1>
    <p class="hint">{{ $animal->species }}@if ($animal->breed_type) · {{ $animal->breed_type }}@endif</p>

    @if ($animal->primaryPhotoUrl())
        <img class="photo" src="{{ $animal->primaryPhotoUrl() }}" alt="Primary photo of {{ $animal->name }}">
    @endif

    <table>
        <tr><th>Sex</th><td>{{ $animal->sex->label() }}</td></tr>
        <tr><th>Date of birth</th><td>{{ $animal->date_of_birth ? \App\Support\UkDate::format($animal->date_of_birth) : '—' }}</td></tr>
        <tr><th>Age (years)</th><td>{{ $animal->age_years ?? '—' }}</td></tr>
        <tr><th>Colour</th><td>{{ $animal->colour ?: '—' }}</td></tr>
        <tr><th>Identifying code</th><td>{{ $animal->identifying_code ?: '—' }}</td></tr>
        <tr><th>Location</th><td>{{ $animal->location ?: '—' }}</td></tr>
        <tr><th>Enclosure</th><td>{{ $animal->enclosure ?: '—' }}</td></tr>
        <tr><th>CITES</th><td>{{ $animal->cites ?: '—' }}</td></tr>
        <tr><th>DWA</th><td>{{ $animal->dwa ?: '—' }}</td></tr>
        <tr><th>Bonded animals</th><td>{{ $animal->bonded_animals ?: '—' }}</td></tr>
        <tr><th>Entry reason</th><td>{{ $animal->entry_reason ?: '—' }}</td></tr>
        <tr><th>Flags</th><td>{{ $animal->flags ?: '—' }}</td></tr>
        <tr><th>Non-shelter</th><td>{{ $animal->non_shelter ? 'Yes' : 'No' }}</td></tr>
        <tr><th>Deceased</th><td>{{ $animal->deceased_at ? \App\Support\UkDate::format($animal->deceased_at) : '—' }}</td></tr>
        <tr><th>Death reason</th><td>{{ $animal->death_reason ?: '—' }}</td></tr>
    </table>

    @if (auth()->user()?->role?->canManageAnimals())
        <p><a class="button" href="{{ route('animals.edit', $animal) }}">Edit</a></p>
    @endif
@endsection
