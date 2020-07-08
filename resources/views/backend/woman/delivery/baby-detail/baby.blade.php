@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            
                <div class="panel panel-default">
                <div class="panel-heading">
                    Baby Info
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>S.N</th>  
                                    <th>Mother Name</th>                                     
                                    <th>Gender</th>    
                                    <th>Weight</th>    
                                    <th>Premature Birth</th>    
                                    <th>Baby Alive</th>    
                                    <th>Baby Status</th>    
                                    <th>Advice</th>
                                    <th>Created At</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i = 0; @endphp
                                @foreach($babyDetails as $babyDetail)
                                @php $i++ @endphp
                                <tr>
                                    <td>{{ $i }}</td> 
                                    <td>
                                        {{\App\Models\BabyDetail::womanNameByChildToken($babyDetail->token)}}
                                    </td>                                     
                                    <td>
                                        @if($babyDetail->gender=='M')
                                            Male
                                        @elseif($babyDetail->gender=='F')
                                            Female
                                        @else
                                            Other
                                        @endif
                                    </td>      
                                    <td>{{$babyDetail->weight}}</td>      
                                    <td>{{$babyDetail->premature_birth}}</td>      
                                    <td>{{$babyDetail->baby_alive}}</td>      
                                    <td>{{$babyDetail->baby_status}}</td>      
                                    <td>{{$babyDetail->advice}}</td>
                                    <td>{{$babyDetail->created_at->diffForHumans()}}</td>
                                    <td>
                                        <a  href="{{route('baby-detail.baby-show', $babyDetail->id) }}">
                                            <i class="fa fa-search-plus"></i>
                                        </a>
                                    </form>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                        </table>
                    </div>
                    <!-- /.dataTable_wrapper -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
@endsection

