@extends('layouts.app')

@section('title', 'Edit '.$animal->name.' · '.config('app.name'))

@section('content')
    <h1>Edit {{ $animal->name }}</h1>

    <form method="post" action="{{ route('animals.update', $animal) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('animals._form')
        <button type="submit">Save animal</button>
        <a class="button" href="{{ route('animals.show', $animal) }}">Cancel</a>
    </form>
@endsection
