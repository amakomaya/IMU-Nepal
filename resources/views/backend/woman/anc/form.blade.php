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
                   $.get("{{route("anc.index")}}",{ woman_id: {{$woman->id}} } ,function(data)
                  {   
                    $("#tabs_info").fadeTo(200,0.1,function()  //start fading the messagebox
                     {
                      $(this).html(data).fadeTo(900,1);
                     });
                  }); 
               },
           error:function(msg){
            data = msg.responseJSON.errors;


                if(data.visit_date!=""){
                    $("#form_visit_date").addClass('has-error');
                    $("#msg_visit_date").text(data.visit_date);
                }

                if(data.weight!=""){
                    $("#form_weight").addClass('has-error');
                    $("#msg_weight").text(data.weight);
                }

                if(data.anemia!=""){
                    $("#form_anemia").addClass('has-error');
                    $("#msg_anemia").text(data.anemia);
                }

                if(data.swell!=""){
                    $("#form_swell").addClass('has-error');
                    $("#msg_swell").text(data.swell);
                }

                if(data.blood_pressure!=""){
                    $("#form_blood_pressure").addClass('has-error');
                    $("#msg_blood_pressure").text(data.blood_pressure);
                }

                if(data.uterus_height!=""){
                    $("#form_uterus_height").addClass('has-error');
                    $("#msg_uterus_height").text(data.uterus_height);
                }

                if(data.baby_presentation!=""){
                    $("#form_baby_presentation").addClass('has-error');
                    $("#msg_baby_presentation").text(data.baby_presentation);
                }

                if(data.baby_heart_beat!=""){
                    $("#form_baby_heart_beat").addClass('has-error');
                    $("#msg_baby_heart_beat").text(data.baby_heart_beat);
                }

                if(data.other!=""){
                    $("#form_other").addClass('has-error');
                    $("#msg_other").text(data.other);
                }

                if(data.iron_pills!=""){
                    $("#form_iron_pills").addClass('has-error');
                    $("#msg_iron_pills").text(data.iron_pills);
                }

                if(data.worm_medicine!=""){
                    $("#form_worm_medicine").addClass('has-error');
                    $("#msg_worm_medicine").text(data.worm_medicine);
                }

                if(data.td_vaccine!=""){
                    $("#form_td_vaccine").addClass('has-error');
                    $("#msg_td_vaccine").text(data.td_vaccine);
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
        Anc Form : {{$woman->name}}
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
<!-- <form class="form-horizontal" role="form" method="POST" action = "@yield('action')"> -->
<form class="form-horizontal" role="form" method="POST" id="form">
                {{ csrf_field() }}
                @yield('methodField')

                    <div class="form-group{{ $errors->has('visit_date') ? ' has-error' : '' }}" id="form_visit_date">
                        <label for="visit_date" class="col-md-3 control-label">Visit Date</label>

                        <div class="col-md-7">
                            <input id="visit_date" type="text" class="form-control" name="visit_date" value="@yield('visit_date')">

                            <span class="help-block">
                                    <strong id = "msg_visit_date"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}" id="form_weight">
                        <label for="weight" class="col-md-3 control-label">Weight</label>

                        <div class="col-md-7">
                            <input id="weight" type="text" class="form-control" name="weight" value="@yield('weight')"  >

                            <span class="help-block">
                                    <strong id = "msg_weight"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('anemia') ? ' has-error' : '' }}" id="form_anemia">
                        <label for="anemia" class="col-md-3 control-label">Anemia</label>

                        <div class="col-md-7">
                            <input id="anemia" type="text" class="form-control" name="anemia" value="@yield('anemia')"  >

                            <span class="help-block">
                                    <strong id = "msg_anemia"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('swell') ? ' has-error' : '' }}" id="form_swell">
                        <label for="swell" class="col-md-3 control-label">Swell</label>

                        <div class="col-md-7">
                            <input id="swell" type="text" class="form-control" name="swell" value="@yield('swell')"  >

                            <span class="help-block">
                                    <strong id = "msg_swell"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('blood_pressure') ? ' has-error' : '' }}" id="form_blood_pressure">
                        <label for="blood_pressure" class="col-md-3 control-label">Blood Pressure</label>

                        <div class="col-md-7">
                            <input id="blood_pressure" type="text" class="form-control" name="blood_pressure" value="@yield('blood_pressure')"  >

                            <span class="help-block">
                                    <strong id = "msg_blood_pressure"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('uterus_height') ? ' has-error' : '' }}" id="form_uterus_height">
                        <label for="uterus_height" class="col-md-3 control-label">Uterus Height</label>

                        <div class="col-md-7">
                            <input id="uterus_height" type="text" class="form-control" name="uterus_height" value="@yield('uterus_height')"  >

                            <span class="help-block">
                                    <strong id = "msg_uterus_height"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('baby_presentation') ? ' has-error' : '' }}" id="form_baby_presentation">
                        <label for="baby_presentation" class="col-md-3 control-label">Baby Presentation</label>

                        <div class="col-md-7">
                            <input id="baby_presentation" type="text" class="form-control" name="baby_presentation" value="@yield('baby_presentation')"  >

                            <span class="help-block">
                                    <strong id = "msg_baby_presentation"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('baby_heart_beat') ? ' has-error' : '' }}" id="form_baby_heart_beat">
                        <label for="baby_heart_beat" class="col-md-3 control-label">Baby Heart Beat</label>

                        <div class="col-md-7">
                            <input id="baby_heart_beat" type="text" class="form-control" name="baby_heart_beat" value="@yield('baby_heart_beat')"  >

                            <span class="help-block">
                                    <strong id = "msg_baby_heart_beat"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('other') ? ' has-error' : '' }}" id="form_other">
                        <label for="other" class="col-md-3 control-label">Other</label>

                        <div class="col-md-7">
                            <input id="other" type="text" class="form-control" name="other" value="@yield('other')"  >

                            <span class="help-block">
                                    <strong id = "msg_other"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('iron_pills') ? ' has-error' : '' }}" id="form_iron_pills">
                        <label for="iron_pills" class="col-md-3 control-label">Iron Pills</label>

                        <div class="col-md-7">
                            <input id="iron_pills" type="text" class="form-control" name="iron_pills" value="@yield('iron_pills')"  >

                            <span class="help-block">
                                    <strong id = "msg_iron_pills"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('worm_medicine') ? ' has-error' : '' }}" id="form_worm_medicine">
                        <label for="worm_medicine" class="col-md-3 control-label">Worm Medicine</label>

                        <div class="col-md-7">
                            <input id="worm_medicine" type="text" class="form-control" name="worm_medicine" value="@yield('worm_medicine')"  >

                            <span class="help-block">
                                    <strong id = "msg_worm_medicine"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('td_vaccine') ? ' has-error' : '' }}"  id="form_td_vaccine">
                        <label for="td_vaccine" class="col-md-3 control-label">Td Vaccine</label id="form_td_vaccine">

                        <div class="col-md-7">
                            <input id="td_vaccine" type="text" class="form-control" name="td_vaccine" value="@yield('td_vaccine')"  >

                            <span class="help-block">
                                    <strong id = "msg_td_vaccine"></strong>
                            </span>
                        </div>
                    </div>

				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}" id="form_status">
                    <label for="status" class="col-md-3 control-label">Status</label>
                    @php($list = [1=>'Enable',0=>'Disable'])
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