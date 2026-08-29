@extends('layouts.app')

@section('title', 'Animals · '.config('app.name'))

@section('content')
    <h1>Animals</h1>
    <p class="hint">Exotic species stay free-text. Search by name, species, code, location, enclosure, CITES, or DWA.</p>

    <form class="filters" method="get" action="{{ route('animals.index') }}">
        <div>
            <label for="q">Find</label>
            <input id="q" name="q" type="text" value="{{ $q }}">
        </div>
        <div>
            <label for="location">Location</label>
            <select id="location" name="location">
                <option value="">All locations</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc }}" @selected($location === $loc)>{{ $loc }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Species</th>
                <th>Location</th>
                <th>Enclosure</th>
                <th>CITES</th>
                <th>DWA</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($animals as $animal)
                <tr>
                    <td>
                        @if ($animal->primaryPhotoUrl())
                            <img class="thumb" src="{{ $animal->primaryPhotoUrl() }}" alt="">
                        @endif
                    </td>
                    <td><a href="{{ route('animals.show', $animal) }}">{{ $animal->name }}</a></td>
                    <td>{{ $animal->species }}</td>
                    <td>{{ $animal->location }}</td>
                    <td>{{ $animal->enclosure }}</td>
                    <td>{{ $animal->cites }}</td>
                    <td>{{ $animal->dwa }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No animals match.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $animals->links() }}
@endsection
