@extends('layouts.master')

@section('title')
    Practice
@endsection

@section('content')
    <h1>Practice</h1>
    <p>Note to self: Practice 8 selects all rows from Books table and orders by title, so is a good one to use to test database is running correctly.</p>
    @foreach($methods as $method)
        <a href='{{ str_replace('practice', '/practice/', $method) }}'> {{ $method }}</a><br>
    @endforeach
@endsection

