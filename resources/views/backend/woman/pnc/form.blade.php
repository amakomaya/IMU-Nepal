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
                   $.get("{{route("pnc.index")}}",{ woman_id: {{$woman->id}} } ,function(data)
                  {   
                    $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                     {
                      $(this).html(data).fadeTo(900,1);
                     });
                  }); 
               },
           error:function(msg){
            data = msg.responseJSON.errors;

                if(data.delivery_date!=""){
                    $("#form_delivery_date").addClass('has-error');
                    $("#msg_delivery_date").text(data.delivery_date);
                }
                if(data.delivery_time!=""){
                    $("#form_delivery_time").addClass('has-error');
                    $("#msg_delivery_time").text(data.delivery_time);
                }
                if(data.mother_status!=""){
                    $("#form_mother_status").addClass('has-error');
                    $("#msg_mother_status").text(data.mother_status);
                }
                if(data.baby_status!=""){
                    $("#form_baby_status").addClass('has-error');
                    $("#msg_baby_status").text(data.baby_status);
                }
                if(data.advice!=""){
                    $("#form_advice").addClass('has-error');
                    $("#msg_advice").text(data.advice);
                }

                if(data.status!=""){
                    $("#form_status").addClass('has-error');
                    $("#msg_status").text(data.status);
                }
                
            } 
         });

    return false; // avoid to execute the actual submit of the form.


});
</script>
<div id ="result_form">
<div class="panel panel-default">
    <div class="panel-heading">
        Pnc Form : {{$woman->name}}
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
<!-- <form class="form-horizontal" role="form" method="POST" action = "@yield('action')"> -->
<form class="form-horizontal" role="form" method="POST" id="form">
                {{ csrf_field() }}
                @yield('methodField')

                    

                    <div class="form-group" id="form_delivery_date">
                        <label for="delivery_date" class="col-md-3 control-label">Delivery Date</label>

                        <div class="col-md-7">
                            <input id="delivery_date" type="text" class="form-control" name="delivery_date" value="@yield('delivery_date')" >

                        <span class="help-block">
                                    <strong id = "msg_delivery_date"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_delivery_time">
                        <label for="delivery_time" class="col-md-3 control-label">Delivery Time</label>

                        <div class="col-md-7">
                            <input id="delivery_time" type="text" class="form-control" name="delivery_time" value="@yield('delivery_time')" >

                        <span class="help-block">
                                    <strong id = "msg_delivery_time"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_mother_status">
                        <label for="mother_status" class="col-md-3 control-label">Mother Status</label>

                        <div class="col-md-7">
                            <input id="mother_status" type="text" class="form-control" name="mother_status" value="@yield('mother_status')" >

                        <span class="help-block">
                                    <strong id = "msg_mother_status"></strong>
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

                    <div class="form-group" id="form_status">
                        <label for="status" class="col-md-3 control-label">Status</label>
                        @php($list = [1=>'Yes',0=>'No'])
                        <div class="col-md-7">
                        <select id="status" class="form-control" name="status" >
                                <option value="">Select Status</option>
                                    @foreach ($list as $key => $value )
                                    <option value="{{ $key }}" @if($status=="$key")               {{ 'selected' }} @endif >
                                       {{ $value }}
                                   </option>
                                @endforeach
                        </select>

                            <span class="help-block">
                                        <strong id = "msg_status"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="woman_id" value="{{$woman->id}}">
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button id="submit" class="btn btn-success">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>