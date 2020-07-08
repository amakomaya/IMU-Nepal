<script type="text/javascript">
/* attach a submit handler to the form */
$("#submitDelivery").click(function(){
    

var url = "@yield('action')"; // the script where you handle the form input.

 $.ajax({
           type: "POST",
           url: url,
           data: $("#formDelievery").serialize(), // serializes the form's elements.  
           beforeSend: function () {
                //$("#error").html("Loading...").fadeIn("4000");
            },
           success: function(data)
               {
                   $.get("{{route("delivery.index")}}",{ woman_id: {{$woman->id}} } ,function(data)
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
                if(data.delivery_place!=""){
                    $("#form_delivery_place").addClass('has-error');
                    $("#msg_delivery_place").text(data.delivery_place);
                }
                if(data.presentation!=""){
                    $("#form_presentation").addClass('has-error');
                    $("#msg_presentation").text(data.presentation);
                }
                if(data.delivery_type!=""){
                    $("#form_delivery_type").addClass('has-error');
                    $("#msg_delivery_type").text(data.delivery_type);
                }
                if(data.compliexicty!=""){
                    $("#form_compliexicty").addClass('has-error');
                    $("#msg_compliexicty").text(data.compliexicty);
                }
                if(data.other_problem!=""){
                    $("#form_other_problem").addClass('has-error');
                    $("#msg_other_problem").text(data.other_problem);
                }
                if(data.advice!=""){
                    $("#form_advice").addClass('has-error');
                    $("#msg_advice").text(data.advice);
                }
                if(data.miscarriage_status!=""){
                    $("#form_miscarriage_status").addClass('has-error');
                    $("#msg_miscarriage_status").text(data.miscarriage_status);
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
                            Delivery Form : {{$woman->name}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
<!-- <form class="form-horizontal" role="form" method="POST" action = "@yield('action')"> -->
<form class="form-horizontal" role="form" method="POST" id="formDelievery">
            <!-- <form class="form-horizontal" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data"> -->
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

                    <div class="form-group" id="form_delivery_place">
                        <label for="delivery_place" class="col-md-3 control-label">Delivery Place</label>

                        <div class="col-md-7">
                            <input id="delivery_place" type="text" class="form-control" name="delivery_place" value="@yield('delivery_place')" >

                        <span class="help-block">
                                    <strong id = "msg_delivery_place"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_presentation">
                        <label for="presentation" class="col-md-3 control-label">Presentation</label>

                        <div class="col-md-7">
                            <input id="presentation" type="text" class="form-control" name="presentation" value="@yield('presentation')" >

                        <span class="help-block">
                                    <strong id = "msg_presentation"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_delivery_type">
                        <label for="delivery_type" class="col-md-3 control-label">Delivery Type</label>

                        <div class="col-md-7">
                            <input id="delivery_type" type="text" class="form-control" name="delivery_type" value="@yield('delivery_type')" >

                        <span class="help-block">
                                    <strong id = "msg_delivery_type"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_compliexicty">
                        <label for="compliexicty" class="col-md-3 control-label">Compliexicty</label>

                        <div class="col-md-7">
                            <input id="compliexicty" type="text" class="form-control" name="compliexicty" value="@yield('compliexicty')" >

                        <span class="help-block">
                                    <strong id = "msg_compliexicty"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_other_problem">
                        <label for="other_problem" class="col-md-3 control-label">Other Problem</label>

                        <div class="col-md-7">
                            <input id="other_problem" type="text" class="form-control" name="other_problem" value="@yield('other_problem')" >

                        <span class="help-block">
                                    <strong id = "msg_other_problem"></strong>
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

                    <div class="form-group" id="form_miscarriage_status">
                        <label for="miscarriage_status" class="col-md-3 control-label">Miscarriage Status</label>
                        @php($list = [1=>'Active',0=>'Inactive'])
                        <div class="col-md-7">
                            <select id="miscarriage_status" class="form-control" name="miscarriage_status" >
                            <option value="">Select Miscarriage Status</option>
                                @foreach ($list as $key => $value )
                                <option value="{{ $key }}" @if($status=="$key")               {{ 'selected' }} @endif >
                                   {{ $value }}
                               </option>
                            @endforeach
                    </select>

                        <span class="help-block">
                                    <strong id = "msg_miscarriage_status"></strong>
                        </span>
                        </div>
                    </div>
                <div class="form-group" id="form_status">
                    <label for="status" class="col-md-3 control-label">Status</label>
                    @php($list = [1=>'Active',0=>'Inactive'])
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
                    <input type="hidden" name="woman_token" value="{{$woman->token}}">
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button id="submitDelivery" class="btn btn-success">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>