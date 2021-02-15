@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div id="app">
            @if($user = App\Models\Organization::where('token',Auth::user()->token)->get()->first())
                @if($user != null && $user->hospital_type!= 4)
                    <admin-dashboard></admin-dashboard>
                @endif
            @else
                <admin-dashboard></admin-dashboard>
            @endif
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <a href="{{ url('health-professional/add') }}">
                                <img src="{{ asset('images/COVID-health_professional-Pup-up-image.jpg') }}" alt=""
                                     class="img-responsive">
                            </a>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    @if(auth()->user()->role === 'healthpost' || auth()->user()->role === 'municipality')
        <script type="text/javascript">
            $(window).on('load', function () {
                $('#myModal').modal('show');
            });
        </script>
    @endif
@endsection
