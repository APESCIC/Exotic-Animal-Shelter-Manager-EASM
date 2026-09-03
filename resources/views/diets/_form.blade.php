@php
    $dateValue = function ($value): string {
        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->format('Y-m-d');
        }

        return (string) ($value ?? '');
    };
@endphp

<label for="name">Name</label>
<input id="name" type="text" name="name" value="{{ old('name', $diet->name) }}" required maxlength="255" placeholder="e.g. Insectivore mix">

<label for="details">Details</label>
<textarea id="details" name="details" maxlength="5000">{{ old('details', $diet->details) }}</textarea>

<label for="started_on">Started</label>
<input id="started_on" type="date" name="started_on" value="{{ old('started_on', $dateValue($diet->started_on)) }}">

<label for="ended_on">Ended</label>
<input id="ended_on" type="date" name="ended_on" value="{{ old('ended_on', $dateValue($diet->ended_on)) }}">

<label for="notes">Notes</label>
<textarea id="notes" name="notes" maxlength="5000">{{ old('notes', $diet->notes) }}</textarea>
