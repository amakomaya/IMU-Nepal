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
                   $.get("{{route("lab-test.index")}}",{ woman_id: {{$woman->id}} } ,function(data)
                  {   
                    $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                     {
                      $(this).html(data).fadeTo(900,1);
                     });
                  }); 
               },
           error:function(msg){
            data = msg.responseJSON.errors;

                if(data.date!=""){
                    $("#form_date").addClass('has-error');
                    $("#msg_date").text(data.date);
                }
                if(data.urine_protin!=""){
                    $("#form_urine_protin").addClass('has-error');
                    $("#msg_urine_protin").text(data.urine_protin);
                }
                if(data.urine_sugar!=""){
                    $("#form_urine_sugar").addClass('has-error');
                    $("#msg_urine_sugar").text(data.urine_sugar);
                }
                if(data.blood_sugar!=""){
                    $("#form_blood_sugar").addClass('has-error');
                    $("#msg_blood_sugar").text(data.blood_sugar);
                }
                if(data.hbsag!=""){
                    $("#form_hbsag").addClass('has-error');
                    $("#msg_hbsag").text(data.hbsag);
                }
                if(data.vdrl!=""){
                    $("#form_vdrl").addClass('has-error');
                    $("#msg_vdrl").text(data.vdrl);
                }
                if(data.retro_virus!=""){
                    $("#form_retro_virus").addClass('has-error');
                    $("#msg_retro_virus").text(data.retro_virus);
                }
                if(data.other!=""){
                    $("#form_other").addClass('has-error');
                    $("#msg_other").text(data.other);
                }
                
            } 
         });

    return false; // avoid to execute the actual submit of the form.


});
</script>
<div id ="result_form">
<div class="panel panel-default">
    <div class="panel-heading">
        Lab Test Form : {{$woman->name}}
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
<!-- <form class="form-horizontal" role="form" method="POST" action = "@yield('action')"> -->
<form class="form-horizontal" role="form" method="POST" id="form">
                {{ csrf_field() }}
                @yield('methodField')

                  
                    <div class="form-group" id="form_date">
                        <label for="date" class="col-md-3 control-label">Date</label>

                        <div class="col-md-7">
                            <input id="date" type="text" class="form-control" name="date" value="@yield('date')" >

                        <span class="help-block">
                                    <strong id = "msg_date"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_urine_protin">
                        <label for="urine_protin" class="col-md-3 control-label">Urine Protin</label>

                        <div class="col-md-7">
                            <input id="urine_protin" type="text" class="form-control" name="urine_protin" value="@yield('urine_protin')" >

                        <span class="help-block">
                                    <strong id = "msg_urine_protin"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_urine_sugar">
                        <label for="urine_sugar" class="col-md-3 control-label">Urine Sugar</label>

                        <div class="col-md-7">
                            <input id="urine_sugar" type="text" class="form-control" name="urine_sugar" value="@yield('urine_sugar')" >

                        <span class="help-block">
                                    <strong id = "msg_urine_sugar"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_blood_sugar">
                        <label for="blood_sugar" class="col-md-3 control-label">Blood Sugar</label>

                        <div class="col-md-7">
                            <input id="blood_sugar" type="text" class="form-control" name="blood_sugar" value="@yield('blood_sugar')" >

                        <span class="help-block">
                                    <strong id = "msg_blood_sugar"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_hbsag">
                        <label for="hbsag" class="col-md-3 control-label">Hbsag</label>

                        <div class="col-md-7">
                            <input id="hbsag" type="text" class="form-control" name="hbsag" value="@yield('hbsag')" >

                        <span class="help-block">
                                    <strong id = "msg_hbsag"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_vdrl">
                        <label for="vdrl" class="col-md-3 control-label">Vdrl</label>

                        <div class="col-md-7">
                            <input id="vdrl" type="text" class="form-control" name="vdrl" value="@yield('vdrl')" >

                        <span class="help-block">
                                    <strong id = "msg_vdrl"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_retro_virus">
                        <label for="retro_virus" class="col-md-3 control-label">Retro Virus</label>

                        <div class="col-md-7">
                            <input id="retro_virus" type="text" class="form-control" name="retro_virus" value="@yield('retro_virus')" >

                        <span class="help-block">
                                    <strong id = "msg_retro_virus"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group" id="form_other">
                        <label for="other" class="col-md-3 control-label">Other</label>

                        <div class="col-md-7">
                            <input id="other" type="text" class="form-control" name="other" value="@yield('other')" >

                        <span class="help-block">
                                    <strong id = "msg_other"></strong>
                        </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="woman_token" value="{{$woman->token}}">
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