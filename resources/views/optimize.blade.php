@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div id="app">
            {{ $data }}
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection
