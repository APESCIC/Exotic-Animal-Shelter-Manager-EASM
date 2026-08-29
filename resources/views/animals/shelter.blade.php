@extends('layouts.app')

@section('title', 'Shelter view · '.config('app.name'))

@section('content')
    <h1>Shelter view</h1>
    <p class="hint">Animals grouped by location / unit.</p>

    @forelse ($groups as $location => $animals)
        <h2>{{ $location }}</h2>
        <ul>
            @foreach ($animals as $animal)
                <li>
                    <a href="{{ route('animals.show', $animal) }}">{{ $animal->name }}</a>
                    — {{ $animal->species }}
                    @if ($animal->enclosure)
                        · enclosure {{ $animal->enclosure }}
                    @endif
                </li>
            @endforeach
        </ul>
    @empty
        <p>No animals in care yet.</p>
    @endforelse
@endsection
