@extends('layouts.app')

@section('title', $report->type->label().' · '.$report->species.' · '.config('app.name'))

@section('content')
    <h1>{{ $report->type->label() }}: {{ $report->species }}</h1>
    <p class="hint">Reported {{ \App\Support\UkDate::format($report->reported_at) }}
        @if ($report->location_area) · {{ $report->location_area }} @endif
    </p>

    <table>
        <tr><th>Type</th><td>{{ $report->type->label() }}</td></tr>
        <tr><th>Species</th><td>{{ $report->species }}</td></tr>
        <tr><th>Colour</th><td>{{ $report->colour ?: '—' }}</td></tr>
        <tr><th>Markings</th><td>{{ $report->markings ?: '—' }}</td></tr>
        <tr><th>Identifying code</th><td>{{ $report->identifying_code ?: '—' }}</td></tr>
        <tr><th>Location / area</th><td>{{ $report->location_area ?: '—' }}</td></tr>
        <tr><th>Reported</th><td>{{ \App\Support\UkDate::format($report->reported_at) }}</td></tr>
        <tr><th>Contact</th><td>
            @if ($report->person)
                <a href="{{ route('people.show', $report->person) }}">{{ $report->person->name }}</a>
            @else
                —
            @endif
        </td></tr>
        <tr><th>Confirmed match</th><td>
            @if ($report->matchedAnimal)
                <a href="{{ route('animals.show', $report->matchedAnimal) }}">{{ $report->matchedAnimal->name }}</a>
                ({{ $report->matchedAnimal->species }})
            @else
                —
            @endif
        </td></tr>
        <tr><th>Notes</th><td>{{ $report->notes ?: '—' }}</td></tr>
    </table>

    <h2>Likely matches</h2>
    <p class="hint">Animals in care or adopted in the last {{ \App\Services\LostFoundMatcher::RECENT_ADOPTION_DAYS }} days, ranked by species, identifying code, colour, and location overlap.</p>

    <table>
        <thead>
            <tr>
                <th>Animal</th>
                <th>Species</th>
                <th>Code</th>
                <th>Score</th>
                <th>Why</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($matches as $match)
                <tr>
                    <td><a href="{{ route('animals.show', $match['animal']) }}">{{ $match['animal']->name }}</a></td>
                    <td>{{ $match['animal']->species }}</td>
                    <td>{{ $match['animal']->identifying_code ?: '—' }}</td>
                    <td>{{ $match['score'] }}</td>
                    <td>{{ implode(', ', $match['reasons']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No likely matches yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (auth()->user()?->role?->canManageLostFound())
        <p><a class="button" href="{{ route('lost-found.edit', $report) }}">Edit</a>
            <a class="button" href="{{ route('lost-found.index') }}">All reports</a></p>
    @else
        <p><a class="button" href="{{ route('lost-found.index') }}">All reports</a></p>
    @endif
@endsection
