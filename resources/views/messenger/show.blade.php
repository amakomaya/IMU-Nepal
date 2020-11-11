@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

{{--            <div class="col-md-6">--}}
        <h1>{{ $thread->subject }}</h1>
        @each('messenger.partials.messages', $thread->messages, 'message')

        @include('messenger.partials.form-message')
{{--    </div>--}}
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script language="JavaScript">
        $( '.toggle-checkbox' ).click( function () {
            $( 'input[type="checkbox"]' ).prop('checked', this.checked)
        })
    </script>
@endsection