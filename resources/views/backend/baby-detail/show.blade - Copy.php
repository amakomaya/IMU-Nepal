@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Baby Details : {{$data->baby_name}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
           
                            <script type="text/javascript">
                                         function confirmDelete(){
                                            if(confirm("Are you sure to delete?")){
                                                                    return true;
                                                                }
                                                                
                                                                else
                                                                {
                                                                    return false;
                                                                }
                                        }
                            </script>

                            <a class="btn btn-primary" href="{{ route('child.edit', $data->id )}}">Edit</a>
                                
                            <table class="table table-striped table-bordered detail-view">
                                            <tbody>
                                     
                                        <tr>
                                            <th width="30%">Token</th>
                                            <td>{{$data->token}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Delivery Token</th>
                                            <td>{{$data->delivery_token}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($data->gender=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Weight</th>
                                            <td>{{$data->weight}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Premature Birth</th>
                                            <td>
                                                @if($data->premature_birth=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Alive</th>
                                            <td>
                                                @if($data->baby_alive=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Status</th>
                                            <td>
                                                @if($data->baby_status=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Advice</th>
                                            <td>{{$data->advice}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Hp Code</th>
                                            <td>
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Birth Certificate Reg No</th>
                                            <td>{{$data->birth_certificate_reg_no}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Family Record Form No</th>
                                            <td>{{$data->family_record_form_no}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Name</th>
                                            <td>{{$data->baby_name}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Child Information By</th>
                                            <td>{{$data->child_information_by}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Grand Father Name</th>
                                            <td>{{$data->grand_father_name}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Grand Mother Name</th>
                                            <td>{{$data->grand_mother_name}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Father Citizenship No</th>
                                            <td>{{$data->father_citizenship_no}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Mother Citizenship No</th>
                                            <td>{{$data->mother_citizenship_no}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Local Registrar Fullname</th>
                                            <td>{{$data->local_registrar_fullname}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($data->status=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{$data->created_at->diffForHumans()}}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{$data->updated_at->diffForHumans()}}</td>
                                        </tr>                             
                                </tbody>
                                    </table>
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

