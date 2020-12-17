@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div id="app">
            <div class="container">
                <div class="card bg-light mt-3">
                    <div class="card-header">
                        Laravel 5.7 Import Export Excel to database Example - ItSolutionStuff.com
                    </div>
                    <div class="card-body">
                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" class="form-control">
                            <br>
                            <button class="btn btn-success">Import User Data</button>
                            <a class="btn btn-warning" href="{{ route('export') }}">Export User Data</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection
