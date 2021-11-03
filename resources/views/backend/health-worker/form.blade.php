@extends('layouts.backend.app')

@section('content')
    
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @if (isset($data))
                                Edit User
                            @else
                                Create User
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                			<form class="form-horizontal" id="hw-form" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @yield('methodField')

                				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको नाम लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Name</label>

                                    <div class="col-md-7">
                                        <input id="name" type="text" class="form-control" name="name" value="@yield('name')" >

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}" hidden>
                                    <label for="province_id" class="col-md-3 control-label">Province</label>
                                    
                                    <div class="col-md-7">
                                    <select id="province_id" class="form-control" name="province_id" >
                                            @foreach ($provinces as $province )
                                               <option value="{{ $province->id }}" @if($province_id=="$province->id") {{ 'selected' }} @endif >{{ $province->province_name }}</option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('province_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('province_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('district_id') ? ' has-error' : '' }}" hidden>
                                    <label for="district_id" class="col-md-3 control-label">District</label>
                                    
                                    <div class="col-md-7">
                                    <select id="district_id" class="form-control" name="district_id" >
                                            @foreach ($districts as $district )
                                               <option value="{{ $district->id }}" @if($district_id=="$district->id") {{ 'selected' }} @endif >{{ $district->district_name }}</option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('district_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('district_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('municipality_id') ? ' has-error' : '' }}" hidden>
                                    <label for="municipality_id" class="col-md-3 control-label">Municipality</label>
                                    
                                    <div class="col-md-7">
                                    <select id="municipality_id" class="form-control" name="municipality_id" >
                                            @foreach ($municipalities as $municipality )
                                               <option value="{{ $municipality->id }}" @if($municipality_id=="$municipality->id") {{ 'selected' }} @endif >{{ $municipality->municipality_name }}</option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('municipality_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('municipality_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                    
                                    
                                <div class="form-group{{ $errors->has('org_code') ? ' has-error' : '' }}" hidden>
                                    <label for="org_code" class="col-md-3 control-label">Healthpost</label>
                                    
                                    <div class="col-md-7">
                                    <select id="org_code" class="form-control" name="org_code" >
                                            @foreach ($organizations as $healthpost )
                                               <option value="{{ $healthpost->org_code }}" @if($org_code=="$healthpost->org_code") {{ 'selected' }} @endif >{{ $healthpost->name }}</option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('org_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('org_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
                                        <label for="post" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको पद लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Post</label>

                                        <div class="col-md-7">
                                            <input id="post" type="text" class="form-control" name="post" value="@yield('post')" >

                                            @if ($errors->has('post'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('post') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                        <label for="image" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको सही (signature) फोटो खिचेर हाल्नुहोस्। "class="fa fa-info-circle" aria-hidden="true"></i> Signature</label>

                                        <div class="col-md-7">
                                            @if(!empty($data->image))
                                                <img src="{{ Storage::url('health-worker/'.$data->image) }}" /> <br><br>
                                            @endif
                                            <input id="image" type="file" name="image" value="@yield('image')" >

                                            @if ($errors->has('image'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('image') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        <label for="phone" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको फोन नं हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Phone</label>

                                        <div class="col-md-7">
                                            <input id="phone" type="text" class="form-control" name="phone" value="@yield('phone')" >

                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('tole') ? ' has-error' : '' }}">
                                        <label for="tole" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको गाउँ टोलको नाम लेख्नुहिस्।"class="fa fa-info-circle" aria-hidden="true"></i> Tole</label>

                                        <div class="col-md-7">
                                            <input id="tole" type="text" class="form-control" name="tole" value="@yield('tole')" >

                                            @if ($errors->has('tole'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('tole') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!isset($data))
                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-md-3 control-label"><i data-toggle="tooltip" title="आमाकोमाया केयरको लागि Username लेख्नुहोस्। सजिलोको लागि स्वास्थ्यकर्मीको फोन नं हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Username</label>

                                            <div class="col-md-7">
                                                <input id="username" type="text" class="form-control" name="username" value="@yield('username')" >

                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('username') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-md-3 control-label"><i data-toggle="tooltip" title="पासवर्ड लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Password</label>

                                            <div class="col-md-7">
                                                <input id="password" type="password" class="form-control" name="password" value="@yield('password')" >

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                                <input type="checkbox" onclick="TogglePassword()"> 
                                                <b>Show Password</b> 
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('re_password') ? ' has-error' : '' }}">
                                            <label for="re_password" class="col-md-3 control-label"><i data-toggle="tooltip" title="पासवर्ड पुनः लेख्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Confirm Password</label>

                                            <div class="col-md-7">
                                                <input id="re_password" type="password" class="form-control" name="re_password" value="@yield('re_password')" >

                                                @if ($errors->has('re_password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('re_password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif



                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीको इमेल आई.डी. हाल्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Email</label>

                                        <div class="col-md-7">
                                            <input id="email" type="text" class="form-control" name="email" value="@yield('email')" >

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('longitude') ? ' has-error' : '' }}" hidden>
                                        <label for="longitude" class="col-md-3 control-label">Longitude</label>

                                        <div class="col-md-7">
                                            <input id="longitude" type="text" class="form-control" name="longitude" value="@yield('longitude')" >

                                            @if ($errors->has('longitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('longitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('latitude') ? ' has-error' : '' }}" hidden>
                                        <label for="latitude" class="col-md-3 control-label">Latitude</label>

                                        <div class="col-md-7">
                                            <input id="latitude" type="text" class="form-control" name="latitude" value="@yield('latitude')" >

                                            @if ($errors->has('latitude'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('latitude') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('imei') ? ' has-error' : '' }}">
                                        <label for="imei" class="col-md-3 control-label"><i data-toggle="tooltip" title="स्वास्थ्यकर्मीले प्रयोग गर्ने tablet को IMEI number थाहा छ भने हाल्नुहोस्। यदि थाहा छैन भने यतिकै छोड्दिनुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> IMEI number</label>

                                        <div class="col-md-7">
                                            <input id="imei" type="text" class="form-control" name="imei" value="@yield('imei')" >

                                            @if ($errors->has('imei'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('imei') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="status" class="col-md-3 control-label"><i data-toggle="tooltip" title="Status छान्नुहोस्।"class="fa fa-info-circle" aria-hidden="true"></i> Status</label>
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

                                            @if ($errors->has('status'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                <hr>
                                <div class="form-group">
                                    <strong class="col-md-3 control-label">Assign Permissions</strong>
                                    <?php
                                      $antiRegTestPermission = ["cases-registration", "sample-collection", "cases-list", "lab-received", "antigen-result"];
                                      sort($antiRegTestPermission);
                                      $pcrRegPermission = [ "cases-registration", "sample-collection", "cases-list" ];
                                      sort($pcrRegPermission);
                                      $pcrLabTestPermission = ["lab-received" , "lab-result"];
                                      sort($pcrLabTestPermission);
                                      $pcrRegTestPermission = ["cases-registration", "sample-collection", "cases-list", "lab-received", "lab-result"];
                                      sort($pcrRegTestPermission);
                                      $poeAntigenPermission = ["cases-registration", "sample-collection", "cases-list", "lab-received", "antigen-result", "poe-registration"];
                                      sort($poeAntigenPermission);
                                      $hasAntiRegTestPermission = $hasPcrRegPermission = $hasPcrRegTestPermission = $hasPcrLabTestPermission = $hasPoeAntigenPermission = false;
                                      if(isset($data)){
                                        $allBundeledPermission = ["cases-registration", "sample-collection", "cases-list", "lab-received", "antigen-result", "poe-registration", "lab-result"];
                                        sort($allBundeledPermission);
                                        $allPermissions = $data->user->getAllPermissions()->pluck('name')->toArray();
                                        $userBundeledPermission = [];
                                        foreach($allPermissions as $permission) {
                                          if(in_array($permission, $allBundeledPermission)){
                                            array_push($userBundeledPermission, $permission);
                                          }
                                        }
                                        sort($userBundeledPermission);
                                        $userBundledPermissionSearilized = json_encode($userBundeledPermission);
                                        if($userBundledPermissionSearilized == json_encode($antiRegTestPermission)){
                                          $hasAntiRegTestPermission = true;
                                        }
                                        if($userBundledPermissionSearilized == json_encode($pcrRegPermission)){
                                          $hasPcrRegPermission = true;
                                        }
                                        if($userBundledPermissionSearilized == json_encode($pcrRegTestPermission)){
                                          $hasPcrRegTestPermission = true;
                                        }
                                        if($userBundledPermissionSearilized == json_encode($pcrLabTestPermission)){
                                          $hasPcrLabTestPermission = true;
                                        }
                                        if($userBundledPermissionSearilized == json_encode($poeAntigenPermission)){
                                          $hasPoeAntigenPermission = true;
                                        }
                                      }
                                   ?>
                                    <div class="col-md-7 checkbox">
                                      <div class="row">
                                        <div class="col-lg-12">
                                          <input type="radio" name="permission_bundle" id="anti-reg-test" value = <?php echo json_encode($antiRegTestPermission) ?>
                                            @if($hasAntiRegTestPermission)
                                              checked
                                            @endif
                                          />
                                          <label for="anti-reg-test">Antigen Registration & Test</label>
                                        </div>
                                        <div class="col-lg-12">
                                          <input type="radio" name="permission_bundle" id="pcr-reg" value= <?php echo json_encode($pcrRegPermission); ?>
                                            @if($hasPcrRegPermission)
                                              checked
                                            @endif
                                          />
                                          <label for="pcr-reg">PCR Registration</label>
                                        </div>
                                        <div class="col-lg-12">
                                          <input type="radio" name="permission_bundle" id="pcr-lab" value=<?php echo json_encode($pcrLabTestPermission); ?>
                                            @if($hasPcrLabTestPermission)
                                              checked
                                            @endif
                                          />
                                          <label for="pcr-lab">PCR Lab Received & Result</label>
                                        </div>
                                        <div class="col-lg-12">
                                          <input type="radio" name="permission_bundle" id="pcr-reg-test" value=<?php echo json_encode($pcrRegTestPermission); ?>
                                            @if($hasPcrRegTestPermission)
                                              checked
                                            @endif
                                          />
                                          <label for="pcr-reg-test">PCR Registration & Lab Result</label>
                                        </div>
                                        <div class="col-lg-12">
                                          
                                          <input type="radio" name="permission_bundle" id="poe-reg-test" value=<?php echo json_encode($poeAntigenPermission); ?>
                                            @if($hasPoeAntigenPermission)
                                              checked
                                            @endif
                                          />
                                          <label for="poe-reg-test">POE Antigen</label>
                                        </div>
                                        <div class="col-lg-12">
                                          <hr />
                                        </div>
                                          @foreach($permissions as $permission)
                                              <div class="col-lg-4">
                                                  <label title="{{ $permission->name }}">
                                                      <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            @if(isset($data))
                                                            @if(in_array($permission->name , $allPermissions))
                                                            checked
                                                              @endif
                                                              @endif
                                                      >{!!$permission->name!!}
                                                  </label>
                                                  <hr>
                                              </div>
                                          @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <button type="submit" class="btn btn-success btn-block">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
@endsection

@section('script')
<!-- <script>
  $('#hw-form').submit(function(event) {
    event.preventDefault(); //this will prevent the default submit
    var permissionBundle = $('input[name="permission_bundle"]:checked').val() || '[]';
    
    var individualPermission = $("input[name='permissions[]']:checkbox:checked").map(function(){
      return $(this).val();
    }).get();
    var allPermission = JSON.parse(permissionBundle).concat(individualPermission);
    
    // $(this).unbind('submit').submit(); // continue the submit unbind preventDefault
  });
</script> -->
@endsection
                              