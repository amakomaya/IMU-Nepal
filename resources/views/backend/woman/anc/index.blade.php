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
          $.get("{{route("anc.create")}}",{ woman_id: {{$woman->id}} } ,function(data)
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
          $.get("{{route("anc.show", 1)}}",{ anc_id: id } ,function(data)
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
            Anc Info : {{$woman->name}}
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
		                <tr>
		                    <th>S.N</th>       
		                    <th>Visit Date</th>    
		                    <th>Weight</th>    
		                    <th>Anemia</th>    
		                    <th>Swelling</th>    
		                    <th>Blood Pressure</th>    
		                    <th>Uterus Height</th>    
		                    <th>Baby Presentation</th>    
		                    <th>Baby Heart Beat</th>    
		                    <th>Other</th>       
		                    <th>Checked By</th>                                  
		                    <th>Record Status</th>
		                    <th>Created At</th>
		                    <th></th>
		                </tr>
		                </thead>
		                <tbody>
		                @php $i = 0; @endphp
		                @foreach($ancs as $anc)
		                @php $i++ @endphp
		                <tr>
		                    <td>{{ $i }}</td>            
		                    <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($anc->visit_date)}}</td>      
		                    <td>{{$anc->weight}}</td>      
		                    <td>{{$anc->anemia}}</td>      
		                    <td>{{$anc->swelling}}</td>      
		                    <td>{{$anc->blood_pressure}}</td>      
		                    <td>{{$anc->uterus_height}}</td>      
		                    <td>{{$anc->baby_presentation}}</td>      
		                    <td>{{$anc->baby_heart_beat}}</td>      
		                    <td>{{$anc->other}}</td>    
		                    <td>{{$anc->checked_by}}</td>                                          
		                    <td>
		                        @if($anc->status=='0')
		                            Previouse Pregnancy
		                        @else
		                            Current Pregnanay
		                        @endif
		                    </td>
		                    <td>{{$anc->created_at->diffForHumans()}}</td>
		                    <td>
		                        <a  id="{{$anc->id}}" class="show" href="#">
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

