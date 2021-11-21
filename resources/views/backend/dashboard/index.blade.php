@extends('layouts.backend.app')
@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            @if (\Request::session()->has('message'))
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    {!! Request::session()->get('message') !!}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                   Welcome to the IMU Dashboard

                   @if(auth()->user()->role == 'main' || auth()->user()->role == 'center' || auth()->user()->role == 'province' && session()->get('permission_id') == 1)
                        <button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#noticeModal" style="margin-top: -5px;">Edit Notice</button>
                   @endif
                </div>
                <!-- /.panel-heading -->
                @if(auth()->user()->role == 'healthpost' || auth()->user()->role == 'healthworker')
                @php
                    $lab_report = $hospital_report = '';
                    if($zero_report){
                        if($zero_report->where('type', 1)->first()){
                            $lab_report = 'checked';
                        }
                        if($zero_report->where('type', 2)->first()){
                            $hospital_report = 'checked';
                        }
                    }
                @endphp
                <div class="panel-body">
                    <b>Note: यदि आजको अन्त्य सम्म कुनै रिपोर्टिङ गरिएको छैन भने मात्र चेक बाकसमा टिक गर्नुहोस्:</b>
                    <div style="margin-left: 20px; margin-top: 10px;">
                        <p>Lab Reporting &nbsp; <input type="checkbox" name="lab" id="lab" {{ $lab_report }}></p>
                        <p>Hospital Reporting &nbsp; <input type="checkbox" name="hospital" id="hospital" {{ $hospital_report }}></p>
                    </div>
                </div>
                <hr>
                @endif
                <div class="panel-body">
                    <div>
                        {!! $data->description ?? 'No Notice available' !!}
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    @if(auth()->user()->role == 'municipality' && $is_user == 0)
    <div id="messageModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">सूचना ! सूचना!! सूचना!!!</h4>
                </div>
                <div class="modal-body">
                    Please create only one vaccination verification user.<br>
                    <span class="text-center">Click here <a href="{{ url('admin/municipality-vaccine') }}" class="btn btn-success">Create</a></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- /#page-wrapper -->

<!-- modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noticeModalLabel">Edit Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if($data)
            <form class="form-horizontal" role="form" method="POST" action="{{ route('index.update', $data->id) }}" >
            {{ method_field('PATCH') }}
            @else
            <form class="form-horizontal" role="form" method="POST" action="{{ route('index.store') }}" >
            @endif
            {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <label for="description">Description</label>  
                        </div>                                
                        <div class="col-md-12">
                            <textarea name="description" class="form-control" id="description" placeholder="Description">
                                {{ $data ? $data->description : '' }}
                            </textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ __('create.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    var options = {
		removePlugins : 'image'
 	};
	CKEDITOR.replace( 'description', options);
    $(window).on('load', function () {
        $('#messageModal').modal('show');
    });

    $('#lab').on("change", function() {
        var state = $(this).is(':checked');
        if(state == true){
            $.ajax( {
                type: 'POST',
                url: '/api/v1/zero-reporting',
                data: {
                    check_status : 1,
                    type : 1,
                },

                success: function(data) {
                    alert('Zero Lab Reporting status saved');
                }
            });
        }else {
            $.ajax( {
                type: 'POST',
                url: '/api/v1/zero-reporting',
                data: {
                    check_status : 0,
                    type : 1,
                },

                success: function(data) {
                    alert('Removed Zero Lab Reporting Status');
                }
            });
        }
    });

    $('#hospital').on("change", function() {
        var state = $(this).is(':checked');
        if(state == true){
            $.ajax( {
                type: 'POST',
                url: '/api/v1/zero-reporting',
                data: {
                    check_status : 1,
                    type : 2,
                },

                success: function(data) {
                    alert('Zero Hospital Reporting status saved');
                }
            });
        }else {
            $.ajax( {
                type: 'POST',
                url: '/api/v1/zero-reporting',
                data: {
                    check_status : 0,
                    type : 2,
                },

                success: function(data) {
                    alert('Removed Zero Hospital Reporting Status');
                }
            });
        }
    });

   

</script>
@endsection