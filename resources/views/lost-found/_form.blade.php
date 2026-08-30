@php
    use App\Enums\LostFoundType;
@endphp

<fieldset>
    <legend>Report</legend>
    <label for="type">Type</label>
    <select id="type" name="type" required>
        @foreach (LostFoundType::cases() as $case)
            <option value="{{ $case->value }}" @selected(old('type', $report->type?->value ?? 'lost') === $case->value)>{{ $case->label() }}</option>
        @endforeach
    </select>

    <label for="species">Species</label>
    <input id="species" name="species" type="text" value="{{ old('species', $report->species) }}" required placeholder="Free-text exotic species">

    <label for="colour">Colour</label>
    <input id="colour" name="colour" type="text" value="{{ old('colour', $report->colour) }}">

    <label for="markings">Markings / description</label>
    <input id="markings" name="markings" type="text" value="{{ old('markings', $report->markings) }}">

    <label for="identifying_code">Identifying code</label>
    <input id="identifying_code" name="identifying_code" type="text" value="{{ old('identifying_code', $report->identifying_code) }}">

    <label for="location_area">Location / area</label>
    <input id="location_area" name="location_area" type="text" value="{{ old('location_area', $report->location_area) }}">

    <label for="reported_at">Reported date</label>
    <input id="reported_at" name="reported_at" type="date" value="{{ old('reported_at', $report->reported_at?->format('Y-m-d') ?? $report->getAttributes()['reported_at'] ?? '') }}" required>
</fieldset>

<fieldset>
    <legend>Links</legend>
    <label for="person_id">Reporter / contact</label>
    <select id="person_id" name="person_id">
        <option value="">None</option>
        @foreach ($people as $person)
            <option value="{{ $person->id }}" @selected((string) old('person_id', $report->person_id) === (string) $person->id)>{{ $person->name }}</option>
        @endforeach
    </select>

    <label for="matched_animal_id">Confirmed matched animal</label>
    <select id="matched_animal_id" name="matched_animal_id">
        <option value="">None</option>
        @foreach ($animals as $animal)
            <option value="{{ $animal->id }}" @selected((string) old('matched_animal_id', $report->matched_animal_id) === (string) $animal->id)>{{ $animal->name }} ({{ $animal->species }})</option>
        @endforeach
    </select>

    <label for="notes">Notes</label>
    <textarea id="notes" name="notes">{{ old('notes', $report->notes) }}</textarea>
</fieldset>
