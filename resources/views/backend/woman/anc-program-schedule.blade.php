@extends('layouts.backend.app')

@section('content')

    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Woman Info : {{ ucwords($data->name) }}
                        <p class="pull-right"> LMP Date : {{ $data->lmp_date_en }} </p>
                    </div>
                    <!-- /.panel-heading -->

                    <div class="panel-body">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                        <div id="printable">

                            <h3 style="text-align:center; margin-top:100px"> {{ $data->getHealthpost($data->hp_code) }} </h3>

                            <h5 style="text-align:center"> {{ $data->getHealthPostInfo($data->hp_code)->district->district_name }}
                                , {{ $data->getHealthPostInfo($data->hp_code)->municipality->municipality_name }} </h5>
                            <br>

                            <div style="padding:20px 50px">
                                <div class="row">
                                    <div>
                                        <p>गर्भवती महिलाको नाम,थर : <b>{{ ucwords($data->name) }},</b></p><br>
                                        <div class="pull-right">
                                            {!! QrCode::size(100)->generate($data->token); !!}
                                            <p class="text-left">Search Woman by QrCode</p>
                                        </div>
                                        <p>आखिरी रजस्वला भएको मिति :
                                            <b>{!! \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat($data->lmp_date_en) !!}</b>
                                        </p>
                                        <p>अनुमानित सुत्केरी हुने मिति :
                                            <b>{!! \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat((date('Y-m-d', strtotime($data->lmp_date_en. ' + 280 days')))) !!}</b>
                                        </p>
                                        <hr>
                                    </div>

                                </div>
                                <p>तपाइले कहिले र कति पटक चेक-अप को लागि आउन पर्छ </p>
                                1) पहिलो पटक गर्भवती परीक्षण सेवा , उपचार तथा सल्लाह लिन आउने मिति :
                                <b>{{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::firstVisitDateFromTo($data->lmp_date_en)['from']) }} देखि {{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::firstVisitDateFromTo($data->lmp_date_en)['to']) }} भित्र ( चौथो महिना )</b><br>
                                2) दोस्रो पटक गर्भवती परीक्षण सेवा , उपचार तथा सल्लाह लिन आउने मिति :
                                <b>{{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::secondVisitDateFromTo($data->lmp_date_en)['from']) }}
                                    देखि {{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::secondVisitDateFromTo($data->lmp_date_en)['to']) }} भित्र ( छैठौं महिनामा
                                     )</b> <br>
                                3) तेस्रो पटक गर्भवती परीक्षण सेवा , उपचार तथा सल्लाह लिन आउने मिति :
                                <b>{{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::thirdVisitDateFromTo($data->lmp_date_en)['from']) }}
                                    देखि {{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::thirdVisitDateFromTo($data->lmp_date_en)['to']) }} भित्र ( आठौं महिनामा
                                     )</b> <br>
                                4) चौथो पटक गर्भवती परीक्षण सेवा , उपचार तथा सल्लाह लिन आउने मिति :
                                <b><b>{{ \App\Helpers\DateHelper::convertToNepaliAndDaysMonthYearFormat(\App\Helpers\AncCalculation::forthVisitDateFromTo($data->lmp_date_en)['from']) }} पछि ( नवौं महिनामा )</b></b></p>

                                <p>
                                    Note : Ideally ANC visit once a month till 28 weeks , then every fortnight in 28-34
                                    weeks and once a week after wards till delivery.
                                </p>
                                <br>
                                <p>
                                    स्वास्थ्य संस्थाको सम्पर्क नं.
                                    : {{ $data->getHealthPostInfo($data->hp_code)->phone }} <br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection