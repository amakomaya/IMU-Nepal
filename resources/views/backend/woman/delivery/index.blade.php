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

    $("#create_delivery").click(function()
      {
          $("#result").text("Loading...").fadeIn("slow");
          $.get("{{route("delivery.create")}}",{ woman_id: {{$woman->id}} } ,function(data)
          {   
            $("#result").fadeTo(200,0.1,function()  //start fading the messagebox
             {
              $(this).html(data).fadeTo(900,1);
             });
          }); 
      });


    $(".show").click(function()
      {
          id = $(this).attr('id');
          $("#result").text("Loading...").fadeIn("slow");
          $.get("{{route("delivery.show", 1)}}",{ delivery_id: id } ,function(data)
          {   
            $("#result").fadeTo(200,0.1,function()  //start fading the messagebox
             {
              $(this).html(data).fadeTo(900,1);
             });
          }); 
      });
    $(".child").click(function()
      {
          id = $(this).attr('id');
          $("#result").text("Loading...").fadeIn("slow");
          $.get("{{route("baby-detail.index", 1)}}",{ delivery_id: id } ,function(data)
          {   
            $("#result").fadeTo(200,0.1,function()  //start fading the messagebox
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
<div id="result">
   <!--  <div class="form-group">
        <a class="btn btn-success" id="create_delivery" href="#">Create</a>
    </div> -->
    <div class="panel panel-default">
        <div class="panel-heading">
            Delivery : {{$woman->name}}
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            
            <div class="dataTable_wrapper">
                <table class="table">
                    <thead>
                    <tr>
                        <th>S.N</th>        
                        <th>Delivery Date</th>    
                        <th>Delivery Time</th>    
                        <th>Delivery Place</th>    
                        <th>Presentation</th>    
                        <th>Delivery Type</th>    
                        <th>Complexity</th>    
                        <th>Other Problem</th>    
                        <th>Advice</th>    
                        <th>Miscarriage Status</th> 
                        <th>Delivery By</th>                                     
                        <th>Status</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0; @endphp
                    @foreach($deliveries as $delivery)
                    @php $i++ @endphp
                    <tr>
                        <td>{{ $i }}</td>               
                        <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($delivery->delivery_date)}}</td>      
                        <td>{{$delivery->delivery_time}}</td>      
                        <td>{{$delivery->delivery_place}}</td>      
                        <td>{{$delivery->presentation}}</td>      
                        <td>{{$delivery->delivery_type}}</td>      
                        <td>
                          @php($complex = explode(',',$delivery->complexity))
                          @if($complex[0]=="true")
                              अत्यधिक रक्तश्राव,
                          @endif
                          @if($complex[1]=="true")
                              ≥ १२ घण्टा बेथा लागेको,
                          @endif
                          @if($complex[2]=="true")
                              साल नझरेको
                          @endif
                        </td>      
                        <td>{{$delivery->other_problem}}</td>      
                        <td>{{$delivery->advice}}</td>      
                        <td>
                            @if($delivery->miscarriage_status=='0')
                                No
                            @else
                                Yes
                            @endif
                        </td>                                          
                        <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($delivery->delivery_by)}}</td>                                     
                        <td>
                            @if($delivery->status=='0')
                                Previouse Delivery
                            @else
                                Current Delivery
                            @endif
                        </td>
                        <td>{{$delivery->created_at->diffForHumans()}}</td>
                        <td>
                            <a id = "{{$delivery->id}}" class="show">
                                <i class="fa fa-search-plus"></i>
                            </a>
                            <a id = "{{$delivery->id}}" class="child">
                                <i class="fa fa-child"></i>
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

