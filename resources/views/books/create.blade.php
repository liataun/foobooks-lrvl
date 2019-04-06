{{-- /resources/views/books/create.blade.php --}}
@extends('layouts.master')

@section('title')
    Add a book
@endsection

@section('content')
    <h1>Add a book</h1>

    <form method='POST' action='/books'>
        <div class='details'>* Required fields</div>
        {{ csrf_field() }}

        <label for='title'>* Title</label>
        <input type='text' name='title' id='title' value='{{ old('title') }}'>
        @if($errors->get('title'))
            <div class='error'>{{ $errors->first('title') }}</div>
        @endif

        <label for='author'>* Author</label>
        <input type='text' name='author' id='author'>

        <label for='published_year'>* Published Year (YYYY)</label>
        <input type='text' name='published_year' id='published_year'>

        <label for='cover_url'>* Cover URL</label>
        <input type='text' name='cover_url' id='cover_url' value='http://'>

        <label for='purchase_url'>* Purchase URL </label>
        <input type='text' name='purchase_url' id='purchase_url' value='http://'>

        <input type='submit' value='Add book'>
    </form>
    @if(count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
@endsection