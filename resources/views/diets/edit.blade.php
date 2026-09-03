@extends('layouts.app')

@section('title', 'Edit diet · '.$animal->name.' · '.config('app.name'))

@section('content')
    <h1>Edit diet</h1>
    <p class="hint">{{ $animal->name }} · {{ $animal->species }}</p>

    <form method="post" action="{{ route('diets.update', $diet) }}">
        @csrf
        @method('PUT')
        @include('diets._form')
        <p>
            <button type="submit">Save changes</button>
            <a class="button" href="{{ route('animals.show', $animal) }}">Cancel</a>
        </p>
    </form>
@endsection
