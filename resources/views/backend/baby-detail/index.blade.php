@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div id="app">
                    <baby-list></baby-list>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection



{{--@extends('layouts.backend.app')--}}

{{--@section('content')--}}
{{--    <div id="page-wrapper">--}}
{{--            <!-- /.row -->--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                <!-- <div class="form-group">--}}
{{--                    <a class="btn btn-success" href="{{route('child.create') }}">Create</a>--}}
{{--                </div> -->--}}
{{--            <script type="text/javascript">--}}
{{--                 function confirmDelete(){--}}
{{--                    if(confirm("Are you sure to delete?")){--}}
{{--                                            return true;--}}
{{--                                        }--}}
{{--                                        --}}
{{--                                        else--}}
{{--                                        {--}}
{{--                                            return false;--}}
{{--                                        }--}}
{{--                }--}}
{{--            </script>--}}

{{--            @if (Request::session()->has('message'))--}}
{{--                <div class="alert alert-block alert-success">--}}
{{--                    <button type="button" class="close" data-dismiss="alert">--}}
{{--                        <i class="ace-icon fa fa-times"></i>--}}
{{--                    </button>--}}
{{--                    {!! Request::session()->get('message') !!}--}}

{{--                </div>--}}
{{--            @endif--}}
{{--                    <div class="panel panel-default">--}}
{{--                        <div class="panel-heading">--}}
{{--                            Baby Info--}}
{{--                        </div>--}}
{{--                        <!-- /.panel-heading -->--}}
{{--                        <div class="panel-body">--}}
{{--                            --}}
{{--                                <div class="dataTable_wrapper">--}}
{{--                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">--}}
{{--                                   <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>S.N</th>                                               --}}
{{--                                    <th>Baby Name</th> --}}
{{--                                    <th>Mother Name</th> --}}
{{--                                    <th>Father Name</th>                                  --}}
{{--                                    <th>Gender</th>    --}}
{{--                                    <th>Weight</th>    --}}
{{--                                    <th>Baby Alive</th>                                    --}}
{{--                                    <th>Healthpost</th>                                             --}}
{{--                                    <th>Status</th>--}}
{{--                                    <th>Created At</th>--}}
{{--                                    <th></th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @php $i = 0; @endphp--}}
{{--                                @foreach($babyDetails as $babyDetail)--}}
{{--                                @php $i++ @endphp--}}
{{--                                <tr>--}}
{{--                                    <td>{{ $i }}</td>                                                   --}}
{{--                                    <td>{{$babyDetail->baby_name}}</td>      --}}
{{--                                    <td>@if($babyDetail->mother_name) {{ $babyDetail->mother_name }} @else {{$babyDetail->getMotherName($babyDetail->delivery_token)}} @endif</td>--}}
{{--                                    <td>@if($babyDetail->father_name) {{ $babyDetail->father_name }} @else {{$babyDetail->getFatherName($babyDetail->delivery_token)}} @endif</td>--}}
{{--                                    <td>{{ $babyDetail->gender }}</td>      --}}
{{--                                    <td>{{$babyDetail->weight}}</td>                  --}}
{{--                                    <td>{{$babyDetail->baby_alive}}</td>--}}
{{--                                    <td>{{\App\Models\Healthpost::getHealthpost($babyDetail->hp_code)}}</td>                                                      --}}
{{--                                    <td>--}}
{{--                                        @if($babyDetail->status=='0')--}}
{{--                                        <span class="label label-danger">Inactive</span>--}}

{{--                                    @else--}}
{{--                                    <span class="label label-success">Active</span>--}}

{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>{{$babyDetail->created_at->diffForHumans()}}</td>--}}
{{--                                    <td>--}}
{{--                                        <a href="{{route('child.edit', $babyDetail->id) }}">--}}
{{--                                            <span class="label label-primary fa fa-pencil"> Edit </span>--}}
{{--                                        </a>--}}
{{--                                        <a href="{{route('child.show', $babyDetail->id) }}">--}}
{{--                                            <span class="label label-primary fa fa-file"> Birth Registration Certificate </span>--}}


{{--                                        </a>--}}
{{--                                        --}}
{{--                                        <a href="{{ route('child-report.health-report-card', $babyDetail->id) }}">--}}
{{--                                        <span class="label label-primary fa fa-file"> बाल स्वास्थ्य कार्ड </span>--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <!-- /.table-responsive -->--}}
{{--                        </div>--}}
{{--                        <!-- /.panel-body -->--}}
{{--                    </div>--}}
{{--                    <!-- /.panel -->--}}
{{--                </div>--}}
{{--                <!-- /.col-lg-12 -->--}}
{{--            </div>--}}
{{--            <!-- /.row -->--}}
{{--        </div>--}}
{{--        <!-- /#page-wrapper -->--}}
{{--@endsection--}}
