{{-- /resources/views/books/search.blade.php --}}
@extends('layouts.master')

@section('title')
    Search
@endsection

@section('content')
    <h1>Search</h1>

    <form method='GET' action='/books/search-process'>

        <fieldset>
            <label for='searchTerm'>Search by title:</label>
            <input type='text' name='searchTerm' id='searchTerm' value='{{ $searchTerm }}'>

            <input type='checkbox' name='caseSensitive' {{ ($caseSensitive) ? 'checked' : '' }} >
            <label>case sensitive</label>
        </fieldset>

        <input type='submit' value='Search' class='btn btn-primary'>

    </form>
    @if($searchTerm)
        <h2>Results for query: <em>{{ $searchTerm }}</em></h2>

        @if(count($searchResults) == 0)
            No matches found.
        @else
            <ul>
                @foreach($searchResults as $book)
                    <li><a href='/books/{{$book->id}}'>{{ $book->title }} by {{ $book->author }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
@endsection