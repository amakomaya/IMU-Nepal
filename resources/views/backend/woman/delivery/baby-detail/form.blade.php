<script type="text/javascript">
/* attach a submit handler to the form */
$("#submit").click(function(){
    

var url = "@yield('action')"; // the script where you handle the form input.

 $.ajax({
           type: "POST",
           url: url,
           data: $("#form").serialize(), // serializes the form's elements.  
           beforeSend: function () {
                //$("#error").html("Loading...").fadeIn("4000");
            },
           success: function(data)
               {
                   $.get("{{route("baby-detail.index")}}",{ delivery_id: {{$delivery->id}} } ,function(data)
                  {   
                    $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                     {
                      $(this).html(data).fadeTo(900,1);
                     });
                  }); 
               },
           error:function(msg){
            data = msg.responseJSON.errors;
                
                if(data.gender!=""){
                    $("#form_gender").addClass('has-error');
                    $("#msg_gender").text(data.gender);
                }
                if(data.weight!=""){
                    $("#form_weight").addClass('has-error');
                    $("#msg_weight").text(data.weight);
                }
                if(data.premature_birth!=""){
                    $("#form_premature_birth").addClass('has-error');
                    $("#msg_premature_birth").text(data.premature_birth);
                }
                if(data.baby_alive!=""){
                    $("#form_baby_alive").addClass('has-error');
                    $("#msg_baby_alive").text(data.baby_alive);
                }
                if(data.baby_status!=""){
                    $("#form_baby_status").addClass('has-error');
                    $("#msg_baby_status").text(data.baby_status);
                }
                if(data.advice!=""){
                    $("#form_advice").addClass('has-error');
                    $("#msg_advice").text(data.advice);
                }
             
                
            } 
         });

    return false; // avoid to execute the actual submit of the form.


});
</script>
<!-- <form class="form-horizontal" role="form" method="POST" action = "@yield('action')"> -->
<form class="form-horizontal" role="form" method="POST" id="form">
                {{ csrf_field() }}
                @yield('methodField')

                    <form class="form-horizontal" role="form" method="POST" id="form">
            <!--<form class="form-horizontal" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data">-->
                {{ csrf_field() }}
                @yield('methodField')

                   

                    <div class="form-group" id="form_gender">
                        <label for="gender" class="col-md-3 control-label">Gender</label>
                        @php($list = ["M"=>'Male',"F"=>'Female',"O"=>'Other'])
                        <div class="col-md-7">
                        <select id="gender" class="form-control" name="gender" >
                                <option value="">Select Gender</option>
                                    @foreach ($list as $key => $value )
                                    <option value="{{ $key }}" @if($gender=="$key")               {{ 'selected' }} @endif >
                                       {{ $value }}
                                   </option>
                                @endforeach
                        </select>
                        <span class="help-block">
                                    <strong id = "msg_gender"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_weight">
                        <label for="weight" class="col-md-3 control-label">Weight (In gram)</label>

                        <div class="col-md-7">
                            <input id="weight" type="text" class="form-control" name="weight" value="@yield('weight')" >

                        <span class="help-block">
                                    <strong id = "msg_weight"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_premature_birth">
                        <label for="premature_birth" class="col-md-3 control-label">Premature Birth</label>

                        <div class="col-md-7">
                            <input id="premature_birth" type="text" class="form-control" name="premature_birth" value="@yield('premature_birth')" >

                        <span class="help-block">
                                    <strong id = "msg_premature_birth"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_baby_alive">
                        <label for="baby_alive" class="col-md-3 control-label">Baby Alive</label>

                        <div class="col-md-7">
                            <input id="baby_alive" type="text" class="form-control" name="baby_alive" value="@yield('baby_alive')" >

                        <span class="help-block">
                                    <strong id = "msg_baby_alive"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_baby_status">
                        <label for="baby_status" class="col-md-3 control-label">Baby Status</label>

                        <div class="col-md-7">
                            <input id="baby_status" type="text" class="form-control" name="baby_status" value="@yield('baby_status')" >

                        <span class="help-block">
                                    <strong id = "msg_baby_status"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_advice">
                        <label for="advice" class="col-md-3 control-label">Advice</label>

                        <div class="col-md-7">
                            <input id="advice" type="text" class="form-control" name="advice" value="@yield('advice')" >

                        <span class="help-block">
                                    <strong id = "msg_advice"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="delivery_token" value="{{$delivery->token}}">
                    </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button id="submit" class="btn btn-success">
                            Submit
                        </button>
                    </div>
                </div>
            </form>