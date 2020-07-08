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
          $.get("{{route("lab-test.create")}}",{ woman_id: {{$woman->id}} } ,function(data)
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
          $.get("{{route("lab-test.show", 1)}}",{ lab_test_id: id } ,function(data)
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
            Lab Test : {{$woman->name}}
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                   <thead>
                    <tr>
                        <th>S.N</th>    
                        <th>Test Date</th>
                        <th>Hb</th> 
                        <th>Albumin</th>    
                        <th>Urine Protin</th>    
                        <th>Urine Sugar</th>    
                        <th>Blood Sugar</th>    
                        <th>Hbsag</th>    
                        <th>Vdrl</th>    
                        <th>Retro Virus</th>    
                        <th>Other</th>    
                        <th>Status</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0; @endphp
                    @foreach($labTests as $labTest)
                    @php $i++ @endphp
                    <tr>
                        <td>{{ $i }}</td>     
                        <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($labTest->test_date)}}</td>     
                        <th>{{$labTest->hb}}</th>
                        <th>{{$labTest->albumin}}</th>             
                        <td>{{$labTest->urine_protein}}</td>      
                        <td>{{$labTest->urine_sugar}}</td>      
                        <td>{{$labTest->blood_sugar}}</td>      
                        <td>{{$labTest->hbsag}}</td>      
                        <td>{{$labTest->vdrl}}</td>      
                        <td>{{$labTest->retro_virus}}</td>      
                        <td>{{$labTest->other}}</td>
                        <td>
                            @if($labTest->status=='0')
                                Previouse Pregnancy
                            @else
                                Current Pregnancy
                            @endif
                        </td>
                        <td>{{$labTest->created_at->diffForHumans()}}</td>
                        <td>
                       
                            <a class="show" id="{{$labTest->id}}">
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

