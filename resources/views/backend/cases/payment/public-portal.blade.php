@extends('layouts.backend.app')
@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
  #province-selector, #district-selector, #municipality-selector {
    min-width: 200px;
  }
</style>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
              <h2>Dashboard</h2>
            <select id="province-selector" name="province">
            </select>
            <select id="district-selector" name="district" disabled>
            </select>
            <select id="municipality-selector" name="municipality" disabled>
            </select>
            <h3 id="active-title"></h3>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
      var activeProvince, activeDistrict, activeMunicipality;

      function loadProvince() {
        $.get("/api/province", function(data){
          var provinceDropdown = $('#province-selector');
          $.each(data, function() {
              provinceDropdown.append($("<option />").val(this.id).text(this.province_name));
          });
        });
      }
      
      function generateActiveTitle() {
        var titleHtml;
        console.log(activeProvince)
        if(activeMunicipality) {
          titleHtml = activeMunicipality.name;
        } else if(activeDistrict) {
          titleHtml = activeDistrict.name;
        } else if(activeProvince) {
          titleHtml = activeProvince.name;
        } else {
          titleHtml = "Nepal";
        }
        console.log(titleHtml);
        $('#active-title').html(titleHtml);
      }

      function loadDistrict() {
        $('#district-selector').prop('disabled', false);
        $.get("/api/district?province="+activeProvince.id, function(data){
          var districtDropdown = $('#district-selector');
          $.each(data, function() {
            districtDropdown.append($("<option />").val(this.id).text(this.district_name));
          });
        });
      }

      function loadMunicipality() {
        $("#municipality-selector").prop('disabled', false);
        $.get("/api/municipality?district="+activeDistrict.id, function(data) {

          var municipalityDropdown = $('#municipality-selector');
          $.each(data, function() {
            municipalityDropdown.append($("<option />").val(this.id).text(this.municipality_name+' '+this.type));
          });
        });
      }

      function loadSelect2() {
        $('#province-selector, #district-selector, #municipality-selector').select2({
          placeholder: "Select",
          allowClear: true
        });
      }

      function addDropdownOnChangeListners() {
        $('#province-selector').on('change', function() {
          console.log(this)
          activeProvince = {name: this.text, id: this.value};
          loadDistrict();
          generateActiveTitle();

        });
        $('#district-selector').on('change', function() {
          activeDistrict = {name: this.text, id: this.value};
          loadMunicipality();
          generateActiveTitle();
        });
        $('#municipality-selector').on('change', function() {
          activeMunicipality = {name: this.text, id: this.value};
          generateActiveTitle();
        });
      }

      $(document).ready(function() {
        loadSelect2();
        loadProvince();
        addDropdownOnChangeListners();
        
      })
    </script>
@endsection
