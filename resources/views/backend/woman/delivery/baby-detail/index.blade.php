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
          $.get("{{route("baby-detail.create")}}",{ delivery_id: {{$delivery->id}} } ,function(data)
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
          $.get("{{route('baby-detail.show', 1)}}",{ child_id: id } ,function(data)
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
            Baby Details : {{$womanName}}
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>S.N</th>    
                            <th>Gender</th>    
                            <th>Weight</th>    
                            <th>Premature Birth</th>    
                            <th>Baby Alive</th>    
                            <th>Baby Status</th>    
                            <th>Advice</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i = 0; @endphp
                        @foreach($babyDetails as $babyDetail)
                        @php $i++ @endphp
                        <tr>
                            <td>{{ $i }}</td>              
                            <td>{{$babyDetail->gender}}</td>      
                            <td>{{$babyDetail->weight}}</td>      
                            <td>{{$babyDetail->premature_birth}}</td>      
                            <td>{{$babyDetail->baby_alive}}</td>      
                            <td>{{$babyDetail->baby_status}}

                              @php($complex = explode(',',$babyDetail->baby_status))
                              @if($complex[0]=="true")
                                  स्वस्थ,
                              @endif
                              @if($complex[1]=="true")
                                  तुरुन्त रोएको,
                              @endif
                              @if($complex[2]=="true")
                                  श्वास फेर्न गाह्रो भएको,
                              @endif

                              @if($complex[3]=="true")
                                  विकलाङ्ग
                              @endif
                        
                            </td>      
                            <td>{{$babyDetail->advice}}</td>
                            <td>{{$babyDetail->created_at->diffForHumans()}}</td>
                            <td>{{$babyDetail->updated_at->diffForHumans()}}</td>
                            <td>
                                <a id="{{$babyDetail->id}}" class="show">
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

