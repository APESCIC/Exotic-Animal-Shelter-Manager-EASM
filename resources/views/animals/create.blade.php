@extends('layouts.app')

@section('title', 'Add animal · '.config('app.name'))

@section('content')
    <h1>Add animal</h1>
    <p class="hint">Species is free text — there is no dog/cat pick-list.</p>

    <form method="post" action="{{ route('animals.store') }}" enctype="multipart/form-data">
        @csrf
        @include('animals._form')
        <button type="submit">Create animal</button>
        <a class="button" href="{{ route('animals.index') }}">Cancel</a>
    </form>
@endsection
