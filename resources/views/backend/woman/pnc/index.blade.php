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

    $("#create").click(function()
      {
          $("#request").text("Loading...").fadeIn("slow");
          $.get("{{route("pnc.create")}}",{ woman_id: {{$woman->id}} } ,function(data)
          {   
            $("#request").fadeTo(200,0.1,function()  //start fading the messagebox
             {
              $(this).html(data).fadeTo(900,1);
             });
          }); 
      });


    $(".show").click(function()
      {
          id = $(this).attr('id');
          $("#request").text("Loading...").fadeIn("slow");
          $.get("{{route("pnc.show", 1)}}",{ pnc_id: id } ,function(data)
          {   
            $("#request").fadeTo(200,0.1,function()  //start fading the messagebox
             {
              $(this).html(data).fadeTo(900,1);
             });
          }); 
      });
</script>

@if (Request::session()->has('message'))
    <div class="alert alert-block alert-success">
        <button type="button" class="close" data-dismiss="alert">
            <i class="ace-icon fa fa-times"></i>
        </button>
        {!! Request::session()->get('message') !!}

    </div>
@endif
<div id="request">
    <!-- <div class="form-group">
        <a class="btn btn-success" id="create" href="#">Create</a>
    </div> -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Pnc : {{$woman->name}}
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                   <thead>
                <tr>
                    <th>S.N</th>    
                    <th>Date of Visit</th>
                    <th>Visit Time</th>
                    <th>Mother Status</th>    
                    <th>Baby Status</th>    
                    <th>Advice</th>     
                    <th>Family Plan</th>  
                    <th>Checked By</th>    
                    <th>Hp Code</th>                                     
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php $i = 0; @endphp
                @foreach($pncs as $pnc)
                @php $i++ @endphp
                <tr>
                    <td>{{ $i }}</td>      
                    <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($pnc->date_of_visit)}}</td>
                    <td>{{$pnc->visit_time}}</td>
                    <td>{{$pnc->mother_status}}</td>      
                    <td>{{$pnc->baby_status}}</td>      
                    <td>{{$pnc->advice}}</td>  
                    <td>{{$pnc->family_plan}}</td>    
                    <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($pnc->checked_by)}}</td>      
                    <td>{{$pnc->hp_code}}</td>                                     
                    <td>
                        @if($pnc->status=='0')
                            No
                        @else
                            Yes
                        @endif
                    </td>
                    <td>{{$pnc->created_at->diffForHumans()}}</td>
                    <td>{{$pnc->updated_at->diffForHumans()}}</td>
                    <td>
                        <a class="show" id="{{$pnc->id}}">
                            <i class="fa fa-search-plus"></i>
                        </a>
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
 <!-- /.request -->

