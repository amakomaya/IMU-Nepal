@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <br><br>

            <div id="app">
                <qr-code-generate></qr-code-generate>
            </div>
        
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection