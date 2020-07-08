@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Baby Details : {{$data->baby_name}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                            <i class="fa fa-print"></i> Print
                        </button>
                        <div id = "printable">

                            <div class="birthCertificate" style="padding:20px; font-size:14px; font-family: arial;text-align: justify; line-height:25px;">

                                <style type="text/css">
                                    .border-bottom{
                                        border-bottom:  1px dotted #000;
                                        display: inline;
                                    }
                                </style>

                                <img src="{{ asset('images/nepal-logo.jpeg') }}" width="120" style="position:relative;margin:0;padding:0; top:160px;">

                                <p style="text-align:center;padding-bottom: 20px;">
                                    Schedule–12 <br>
                                    ( Related with Rule 7 )<br>
                                    Government of Nepal <br>
                                    Ministry of Federal Affairs and Local Development <br> 
                                    <strong>Office of Local Registrar </strong> <br>
                                    {{$municipality_name}} <br> 
                                    {{$district_name}} District, {{$province_name}} <br>
                                    <strong style="text-decoration: underline; font-size:25px;">Birth Registration Certificate </strong>
                                </p>

                                <p style="padding-bottom:30px;">
                                    Registration No.:  <strong class="border-bottom">{{$data->birth_certificate_reg_no or ''}}</strong>                 
                                    <span style="float: right;">Date of Registration.:  <strong>{{$data->date_of_birth_reg or '....................'}}</strong></span><br>
                                    Family Record Form No.: <strong class="border-bottom">{{$data->family_record_form_no}}</strong> <br>
                                </p>



                                <p><span style="padding-left:70px;">This is to certify,</span> as per the birth register maintained at this office and the information provided by <strong> <span class="border-bottom">{{$data->child_information_by}}</span> </strong> @if(empty($data->child_information_by)) .............................. @endif in the information from of schedule 2, that <strong>

                                @if($data->gender=="Male")
                                    Mr.
                                @else
                                    Mrs
                                @endif
                                @if(empty($data->baby_name)) .................................... @endif
                                <span class="border-bottom">{{$data->baby_name}}</span> </strong>
                                @if($data->gender=="Male")
                                    son
                                @else
                                    daughter
                                @endif
                                of <strong>Mrs.  <span class="border-bottom">{{$data->getMotherName($data->delivery_token)}}</span> </strong>and <strong>Mr.   <span class="border-bottom">{{$data->getFatherName($data->delivery_token)}}</span> </strong>, </strong>

                                @if($data->gender=="Male")
                                    grandson 
                                @else
                                    grand daughter
                                @endif

                                of                              

                                <strong>
                                @if($data->grand_father_name!="")
                                    Mr.
                                @elseif($data->grand_mother_name)
                                    Mrs.
                                @else
                                    Mr./Mrs.
                                @endif
                                 <span class="border-bottom">

                                @if($data->grand_father_name !="" )
                                    {{$data->grand_father_name}}
                                @elseif($data->grand_mother_name)
                                    {{$data->grand_mother_name}}
                                @else
                                    ............................
                                @endif
                                </span>
                                </strong> , a resident of Ward No <strong> <span class="border-bottom">{{$data->getMotherWardNo($data->delivery_token)}}
                                </span> </strong>, <strong> <span class="border-bottom">{{$data->getMotherTole($data->delivery_token)}}
                                </span> </strong>,</strong> was born on <strong> <span class="border-bottom">{{\App\Helpers\ViewHelper::convertEnglishToNepali($data->getDob($data->delivery_token))}} </span> </strong> 
                                BS  ( <strong> <span class="border-bottom">{{$data->getDob($data->delivery_token)}}</span> </strong> AD ) </strong> in {{ $district_name }}. </p>
                                 
                                <p style="padding-top:20px;">
                                    If Citizenship Certificate is Issued to.<br> Citizenship Certificate No., Issued Date and District:                 
                                    <span style="float: right;padding-right:80px;clear: both;">Local Registrar's :  </span><br>
                                    
                                    <strong>A.Father </strong> : <strong><span class="border-bottom"> {{$data->father_citizenship_no}} </span></strong>. 
                                    <span style="float:right;padding-right:80px;clear: both;"><strong>Signature</strong> : </span><br><br>
                                    
                                    <strong>B. Mother </strong>:  <strong><span class="border-bottom"> {{$data->mother_citizenship_no}} </span></strong>
                                    <span style="float: right;padding-right:80px;clear: both;">Name and surname : <strong><span class="border-bottom"> {{$data->local_registrar_fullname}} </span></strong> </span><br>
                                    @php
                                    

                                    @endphp
                                    <span style="float: right;padding-right:80px;clear: both;">Date : <strong><span class="border-bottom">{{\App\Helpers\ViewHelper::convertEnglishToNepali(date("Y-m-d"))}} </span></strong></span>
                                </p>
                            </div>
                            </div>
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

