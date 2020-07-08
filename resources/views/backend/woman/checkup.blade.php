@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">                    
                    <script>
                      $(document).ready(function(){
                        

                        $.get("{{route("anc.index")}}",{ woman_id: {{$data->id}} } ,function(data)
                        {   
                          $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                           {
                            $(this).html(data).fadeTo(900,1);
                           });
                        }); 

                        $("#tab1").click(function()
                        {
                            $("#tabs_info").text("Loading...").fadeIn("slow");
                            $.get("{{route("anc.index")}}",{ woman_id: {{$data->id}} } ,function(data)
                            {   
                              $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                               {
                                $(this).html(data).fadeTo(900,1);
                               });
                            }); 
                        });



                        $("#tab2").click(function()
                        {
                            $("#tabs_info").text("Loading...").fadeIn("slow");
                            $.get("{{route("delivery.index")}}",{ woman_id: {{$data->id}} } ,function(data)
                            {   
                              $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                               {
                                $(this).html(data).fadeTo(900,1);
                               });
                            }); 
                        });

                        $("#tab3").click(function()
                        {
                            $("#tabs_info").text("Loading...").fadeIn("slow");
                            $.get("{{route("pnc.index")}}",{ woman_id: {{$data->id}} } ,function(data)
                            {   
                              $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                               {
                                $(this).html(data).fadeTo(900,1);
                               });
                            }); 
                        });


                        $("#tab4").click(function()
                        {
                            $("#tabs_info").text("Loading...").fadeIn("slow");
                            $.get("{{route("lab-test.index")}}",{ woman_id: {{$data->id}} } ,function(data)
                            {   
                              $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                               {
                                $(this).html(data).fadeTo(900,1);
                               });
                            }); 
                        });

                      } );
                    </script>
                   
                    <div id="tabs">
                        <ul class="nav nav-tabs">
                          <li><a href="#" id ="tab1">Anc</a></li>
                          <li><a href="#" id ="tab2">Delivery</a></li>
                          <li><a href="#" id ="tab3">PNC</a></li>
                          <li><a href="#" id ="tab4">Lab Test</a></li>
                        </ul>
                        <div id="tabs_info"></div>
                    </div>
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
@endsection
                                  