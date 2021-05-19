@extends('layouts.public.app')
@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>

  #province-selector,
  #district-selector,
  #organization-selector,
  #municipality-selector {
    width: 100%;
  }

  .card{
      margin-top: 0;
      margin-bottom: 1.5rem;
      text-align: left;
      position: relative;
      background: #fff;
      box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
      border-radius: 4px;
      transition: all 0.3s ease;
  }

  .public-content .card:hover {
    cursor: pointer;
    opacity: 0.8;
  }

  .public-content {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .public-content .card {
    min-width: 540px;
    border-radius: 15px;
    color: #FFF;
    padding: 15px;
    margin-bottom: 40px;
  }

  .public-content .card .info-header {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: stretch;
  }

  .public-content .card .info-header .fa {
    font-size: 60px !important;
  }

  .public-content .card .card-body {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-items: center;  
  }
  .hospital-card {
    background-color: #0443A5;
  }

  .general-card {
    background-color: #6A8A82;
    color: #FFF;
  }

  .hdu-card {
    background-color: #0a70ae;
    color: #FFF;
  }

  .icu-card {
    background-color: #17bdce;
    color: #FFF;
  }

  .ventilator-card {
    background-color: #FF9900;
    color: #FFF;
  }

  .oxygen-card {
    background-color: #037650;
    color: #FFF;
  }

  .death-card {
    background-color: #E52165;
    color: #FFF;
  }

  .discharge-card {
    background-color: #077B8A;
    color: #FFF;
  }

  .admission-card {
    background-color: #b30bd5;
    color: #FFF;
  }

  .public-content .modal-dialog {
    width: 90%;
    height: 90%;
  }

  h1.info-count {
    margin: 0;
    text-align: center;
  }

  .modal-dialog{
    position: relative;
    display: table; / This is important /
    overflow-y: auto;
    overflow-x: auto;
    width: auto;
    min-width: 300px;
  }
  .row {
    margin-left: auto;
    margin-right: auto;
  }
</style>
@endsection
@section('content')
<div>
  <div class="row">
    <div class="container">
      <div class="col-lg-12">
        <h2>Dashboard</h2>
        <div class="lg-controls">
          <div class="row">
            <div class="col-md-3">
              <h4 class="text-center">Province</h4>
              <select id="province-selector" name="province">
              </select>
            </div>
            <div class="col-md-3">
            <h4 class="text-center">District</h4>
              <select id="district-selector" name="district" disabled>
              </select>
            </div>
            <div class="col-md-3">
              <h4 class="text-center">Municipality</h4>
              <select id="municipality-selector" name="municipality" disabled>
              </select>
            </div>
            <div class="col-md-3">
              <h4 class="text-center">Organization</h4>
              <select id="organization-selector" name="organization-type">
                <option value="">Select</option>
                <option value="5"> Institutional Isolation</option>
                <option value="3" selected>Lab & Treatment( Hospital )</option>
              </select>
            </div>
          </div>
          
        </div>
        <hr />
        <h2 id="active-title">Nepal</h2>

        <div class="public-content">
          <div class="card hospital-card" data-toggle="modal" data-target="#hospital-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-hospital-o " style="color: #d6ff22;"></i>
                </div>
                <h3>Hospital & Isolation</h3>
              </div>
              <h1  class="info-count" id="hospital-count"></h1>
            </div>
          </div>

          <div class="card general-card insti-isolate" data-toggle="modal" data-target="#general-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-square" style="color: #d6ff22;"></i>
                </div>
                <h3>General</h3>
              </div>
              <h1  class="info-count" id="general-count"></h1>
            </div>
          </div>
          
          <div class="card hdu-card insti-isolate" data-toggle="modal" data-target="#hdu-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-bars" style="color: #d6ff22;"></i>
                </div>
                <h3>HDU</h3>
              </div>
              <h1  class="info-count" id="hdu-count"></h1>
            </div>
          </div>

          <div class="card icu-card insti-isolate" data-toggle="modal" data-target="#icu-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-bed" style="color: #d6ff22;"></i>
                </div>
                <h3>ICU</h3>
              </div>
              <h1  class="info-count" id="icu-count"></h1>
            </div>
          </div>

          <div class="card ventilator-card insti-isolate" data-toggle="modal" data-target="#ventilator-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-heartbeat" style="color: #d6ff22;"></i>
                </div>
                <h3>Ventilators</h3>
              </div>
              <h1 class="info-count" id="ventilator-count"></h1>
            </div>
          </div>

          <div class="card oxygen-card insti-isolate" data-toggle="modal" data-target="#oxygen-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-gears" style="color: #d6ff22;"></i>
                </div>
                <h3>Daily Oxygen <br />Consumption (in lts)</h3>
              </div>
              <h1 class="info-count" id="oxygen-count"></h1>
            </div>
          </div>
        </div>

        <h2 id="active-title">Today's Record</h2>
        <div class="public-content">

          <div class="card discharge-card" data-toggle="modal" data-target="#discharge-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-smile-o" style="color: #d6ff22;"></i>
                </div>
                <h3>Discharge</h3>
              </div>
              <h1 class="info-count" id="discharge-count"></h1>
            </div>
          </div>

          <div class="card admission-card" data-toggle="modal" data-target="#admission-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-plus-square" style="color: #d6ff22;"></i>
                </div>
                <h3>Admission</h3>
              </div>
              <h1 class="info-count" id="admission-count"></h1>
            </div>
          </div>
        
          <div class="card death-card" data-toggle="modal" data-target="#death-modal">
            <div class="card-body">
              <div class="info-header">
                <div class="icon">
                  <i class="fa fa-frown-o" style="color: #d6ff22;"></i>
                </div>
                <h3>Death</h3>
              </div>
              <h1 class="info-count" id="death-count"></h1>
            </div>
          </div>
        </div>

          <div id="hospital-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Hospital & Isolation</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th> 
                        <th>Address</th>
                        <th>Phone No:</th> 
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div id="general-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">General</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Bed Usage</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div id="hdu-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">HDU</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Bed Usage</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div id="icu-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">ICU</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Bed Usage</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div id="ventilator-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Ventilators</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          <div id="oxygen-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Oxygen Facility</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Availability</th>
                        <th>Daily Usage</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>

          </div>

          <div id="discharge-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Discharge</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Availability</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>

          </div>

          <div id="admission-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Admission</h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Availability</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>

          </div>

          <div id="death-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Death </h4>
                </div>
                <div class="modal-body">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>S.N</th>                                      
                        <th>Name</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Municipality</th>
                        <th>Availability</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $('#organization-selector').on('change', function() {
    var org_val = $(this).val();
    if(org_val == 5) {
      $('.insti-isolate').hide();
    } else {
      $('.insti-isolate').show();
    }
  });

  var activeProvince, activeDistrict, activeMunicipality, mainData, activeOrganization;
  var province_id = {!! json_encode($province_id) !!};

  $(function() {

    if(province_id != null) {
      if(activeProvince === undefined) {
        if(province_id === 1) {
          activeProvince = { name: "Province 1", id: province_id };
        }else if(province_id === 2){
          activeProvince = { name: "Province 2", id: province_id };
        }else if(province_id === 3){
          activeProvince = { name: "Bagmati Pradesh", id: province_id };
        }else if(province_id === 4){
          activeProvince = { name: "Gandaki Pradesh", id: province_id };
        }else if(province_id === 5){
          activeProvince = { name: "Province 5", id: province_id };
        }else if(province_id === 6){
          activeProvince = { name: "Karnali Pradesh", id: province_id };
        }else if(province_id === 7){
          activeProvince = { name: "Sudurpashchim Pradesh", id: province_id };
        }
      }
    }
    generateActiveTitle();
    fetchData();
  });


  function fetchData() {
    let params = '?';
    if (activeOrganization === undefined){
        activeOrganization = {name: "Lab & Treatment( Hospital )", id: "3"};
    }

    if(activeMunicipality) {
      params += 'municipality_id='+activeMunicipality.id;
    } else if(activeDistrict) {
      params += 'district_id='+activeDistrict.id;
    } else if(activeProvince) {
      params += 'province_id='+activeProvince.id;
    }
    if(activeOrganization) {
      params += '&organization_type='+activeOrganization.id;
    }
    $.get("api/status"+params, function(data) {
     mainData = data;
     renderData();
    });
  }

  function renderData() {
    if(mainData) {
      renderHospitalTable();
      renderGeneralTable();
      renderHDUTable();
      renderICUTable();
      renderVentilatorTable();
      renderOxygenTable();
      renderDischargeTable();
      renderAdmissionTable();
      renderDeathTable();
      renderDeathTable();
    }
  }

  function renderHospitalTable() {
    let tableDiv = $('#hospital-modal tbody');
    let tableContent = '';
    let totalCount = 0;
    mainData.organizations.forEach(function(item,index){
      tableContent += '<tr><td>'+parseInt(index+1)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+item.address+'</td><td>'+item.phone+'</td></tr>';
      totalCount++;
    });
    $('#hospital-count').html(totalCount);
    tableDiv.html(tableContent);
  }

  function renderHDUTable() {
    let tableDiv = $('#hdu-modal tbody');
    let tableContent = '';
    let totalBedCount = 0;
    let usedBedCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalBedCount += item.total_hdu||0;
      usedBedCount += item.used_hdu||0;
      var itemUsage = item.used_hdu+' / '+item.total_hdu;
      if(item.total_hdu == 0 && item.used_hdu == 0) {} else {
        count_single = ++count;
        tableContent += '<tr><td>'+parseInt(count_single)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+itemUsage+'</td></tr>';
      }
    });
    $('#hdu-count').html(usedBedCount+' / '+ totalBedCount);
    tableDiv.html(tableContent);
  }

  function renderGeneralTable() {
    let tableDiv = $('#general-modal tbody');
    let tableContent = '';
    let totalGeneralCount = 0;
    let usedGeneralCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalGeneralCount += item.total_general||0;
      usedGeneralCount += item.used_general||0;
      var itemUsage = item.used_general+' / '+item.total_general;
      if(item.total_general == 0 && item.used_general == 0) {} else {
        count_single = ++count;
        tableContent += '<tr><td>'+parseInt(count_single)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+itemUsage+'</td></tr>';
      }
    });
    $('#general-count').html(usedGeneralCount+' / '+ totalGeneralCount);
    tableDiv.html(tableContent);
  }

  function renderICUTable() {
    let tableDiv = $('#icu-modal tbody');
    let tableContent = '';
    let totalBedCount = 0;
    let usedBedCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalBedCount += item.total_icu||0;
      usedBedCount += item.used_icu||0;
      var itemUsage = item.used_icu+' / '+item.total_icu;
      if(item.total_icu == 0 && item.used_icu == 0) {} else {
        count_single = ++count;
        tableContent += '<tr><td>'+parseInt(count_single)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+itemUsage+'</td></tr>';
      }
    });
    $('#icu-count').html(usedBedCount+' / '+ totalBedCount);
    tableDiv.html(tableContent);
  }

  function renderVentilatorTable() {
    let tableDiv = $('#ventilator-modal tbody');
    let tableContent = '';
    let totalVentilatorCount = 0;
    let usedVentilatorCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalVentilatorCount += item.total_ventilators||0;
      usedVentilatorCount += item.used_ventilators||0;
      var itemUsage = item.used_ventilators+' / '+item.total_ventilators;
      if(item.total_ventilators == 0 && item.used_ventilators == 0) {} else {
        count_single = ++count;
        tableContent += '<tr><td>'+parseInt(count_single)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+itemUsage+'</td></tr>';
      }
    });
    $('#ventilator-count').html(usedVentilatorCount+' / '+ totalVentilatorCount);
    tableDiv.html(tableContent);
  }

  function renderOxygenTable() {
    let tableDiv = $('#oxygen-modal tbody');
    let tableContent = '';
    let oxygenAvailabilityMap = {
      1: 'Available',
      2: 'Partially Available'
    };
    let totalDailyUsage = 0;
    mainData.organizations.forEach(function(item,index){
      if(item.oxygen_availability===1 || item.oxygen_availability===2) {
        totalDailyUsage += parseFloat(item.daily_capacity_in_liter)||0;
        var itemUsage = item.used_general_hdu+' / '+item.total_general_hdu;
        tableContent += '<tr><td>'+parseInt(index+1)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+oxygenAvailabilityMap[item.oxygen_availability]+'</td><td>'+item.daily_capacity_in_liter+'</td></tr>';
      }
    });
    $('#oxygen-count').html(totalDailyUsage);
    tableDiv.html(tableContent);
  }

  function renderDischargeTable() {
    let tableDiv = $('#discharge-modal tbody');
    let tableContent = '';
    let totalCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalCount += item.today_total_discharge||0;
      if(item.today_total_discharge != 0) {
        tableContent += '<tr><td>'+parseInt(++count)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+item.address+'</td><td>'+item.today_total_discharge+'</td></tr>';
      }
    });
    $('#discharge-count').html(totalCount);
    tableDiv.html(tableContent);
  }

  function renderAdmissionTable() {
    let tableDiv = $('#admission-modal tbody');
    let tableContent = '';
    let totalCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalCount += item.today_total_admission||0;
      if(item.today_total_admission != 0) {
        tableContent += '<tr><td>'+parseInt(++count)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+item.address+'</td><td>'+item.today_total_admission+'</td></tr>';
      }
    });
    $('#admission-count').html(totalCount);
    tableDiv.html(tableContent);
  }

  function renderDeathTable() {
    let tableDiv = $('#death-modal tbody');
    let tableContent = '';
    let totalCount = 0;
    let count = 0;
    mainData.organizations.forEach(function(item,index){
      totalCount += item.today_total_death||0;
      if(item.today_total_death == 0) {} else {
        tableContent += '<tr><td>'+parseInt(++count)+'</td><td>'+item.name+'</td><td>'+item.province_name+'</td><td>'+item.district_name+'</td><td>'+item.municipality_name+'</td><td>'+item.address+'</td><td>'+item.today_total_death+'</td></tr>';
      }
    });
    $('#death-count').html(totalCount);
    tableDiv.html(tableContent);
  }

  function generateActiveTitle() {
    var titleHtml;
    if (activeMunicipality) {
      titleHtml = activeMunicipality.name;
    } else if (activeDistrict) {
      titleHtml = activeDistrict.name;
    } else if (activeProvince) {
      titleHtml = activeProvince.name;
    } else {
      titleHtml = "Nepal";
    }
    $('#active-title').html(titleHtml);
  }

  function resetProvince() {
    activeProvince = null;
    resetDistrict();
    resetMunicipality();
  }

  function resetDistrict() {
    activeDistrict = null;
    if (!activeProvince) {
      $('#district-selector').html('');
      $('#district-selector').prop('disabled', true);
    }
    resetMunicipality();
  }

  function resetMunicipality() {
    activeMunicipality = null;
    if (!activeDistrict) {
      $('#municipality-selector').html('');
      $('#municipality-selector').prop('disabled', true);
    }
  }

  function resetOrganization() {
    activeOrganization = null;
  }

  function loadProvince() {
    $.get("/api/province", function(data) {
      var provinceDropdown = $('#province-selector');
      provinceDropdown.append($("<option />").val(null).text('Select'));
      $.each(data, function() {
        if(province_id == null) {
          provinceDropdown.append($("<option />").val(this.id).text(this.province_name));
        } else {
          if(province_id === this.id) {
            provinceDropdown.append($("<option />").val(this.id).text(this.province_name));
          }
        }
      });
    });
  }

  
  function loadDistrict() {
    $('#district-selector').prop('disabled', false);
    $.get("/api/district?province=" + activeProvince.id, function(data) {
      var districtDropdown = $('#district-selector');
      districtDropdown.append($("<option />").val(null).text('Select'));
      $.each(data, function() {
        districtDropdown.append($("<option />").val(this.id).text(this.district_name));
      });
    });
  }

  function loadMunicipality() {
    $("#municipality-selector").prop('disabled', false);
    $.get("/api/municipality?district=" + activeDistrict.id, function(data) {
      var municipalityDropdown = $('#municipality-selector');
      municipalityDropdown.append($("<option />").val(null).text('Select'));
      $.each(data, function() {
        municipalityDropdown.append($("<option />").val(this.id).text(this.municipality_name + ' ' + this.type));
      });
    });
  }

  function loadSelect2() {
    $('#province-selector, #district-selector, #municipality-selector, #organization-selector').select2({
      placeholder: "Select",
      allowClear: true
    });
  }

  function addDropdownOnChangeListners() {
    $('#province-selector').on('change', function() {
      var selectedValue = this.value;
      if (selectedValue) {
        activeProvince = {
          name: $(this).children("option:selected").text(),
          id: selectedValue
        };
        loadDistrict();
      } else {
        resetProvince();
      }
      generateActiveTitle();
      fetchData();

    });

    $('#district-selector').on('change', function() {
      var selectedValue = this.value;
      if (selectedValue) {
        activeDistrict = {
          name: $(this).children("option:selected").text(),
          id: selectedValue
        };
        loadMunicipality();
      } else {
        resetDistrict();
      }
      generateActiveTitle();
      fetchData();
    });

    $('#municipality-selector').on('change', function() {
      var selectedValue = this.value;
      if (selectedValue) {
        activeMunicipality = {
          name: $(this).children("option:selected").text(),
          id: selectedValue
        };
      } else {
        resetMunicipality();
      }
      generateActiveTitle();
      fetchData();
    });

    $('#organization-selector').on('change', function() {
      var selectedValue = this.value;
      if (selectedValue) {
        activeOrganization = {
          name: $(this).children("option:selected").text(),
          id: selectedValue
        };
      } else {
        resetOrganization();
      }
      fetchData();
    });
  }

  $(document).ready(function() {
    loadSelect2();
    loadProvince();
    addDropdownOnChangeListners();
    fetchData();

  })
</script>
@endsection