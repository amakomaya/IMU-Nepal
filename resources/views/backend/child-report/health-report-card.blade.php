@extends('layouts.backend.app')

@section('content')

<div id="page-wrapper">
    <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
        <i class="fa fa-print"> Print </i> 
    </button>
    
    <div id="printable">
    <style>

        .detail{position: relative;}
        .logo{position:absolute;top:20px;left:50px;}
        .dotted{border-bottom: 1px dotted #333;}
        .txt-title{color:#ac1010;font-weight:bold;}
        .txt-header{color:#082389;font-weight:bold;}
        .txt-content{font-size:16px;line-height:30px;}

        @media only screen and (min-width :800px) {
        .detail{
            width:700px;
        }
        .dotted{
            padding:5px 40px;
        }
        }
        .td{background-color:#5d6f5e;}
    </style>
        <div class="detail col-md-offset-2">
            <img src="{{ asset('images/nepal-logo.jpeg') }}" width="110" class="logo hidden-xs">
            <p class="text-center txt-content txt-title">
                            नेपाल सरकार <br>
                            स्वास्थ्य मनत्रालय  <br>
                            स्वास्थ्य सेवा बिभाग <br> 
                            स्वास्थ्य व्यवस्थापन सूचना प्रणाली <br>
            </p>
            <h3 class="text-center txt-header"> बाल स्वास्थ्य कार्ड </h3>
                <div class="well">
                    <p class="txt-content">
                        मूल दर्ता नं.  <strong class="dotted"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong> गाउँघर क्लिनिक दर्ता नं.  <strong class="dotted"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong> <br>
                        खोप सेवा दर्ता नं. <strong class="dotted"> {{ $child->card_no }} </strong> पोषण  सेवा दर्ता नं. <strong class="dotted"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><br>
                        नवजात शिशु तथा बाल रोगको एकीकृत ब्यवस्थापन सेवा दर्ता नं. <strong class="dotted"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong>
                    </p>
                </div>
                <div class="well">
                    <p class="txt-content"> 
                        बच्चाको नाम थर : <strong class="dotted"> {{ $child->baby_name }} </strong> लिङ्ग : <input type="checkbox" name="F" @if( $child->gender == "Female") checked = "" @endif>  महिला / <input type="checkbox" name="M" @if( $child->gender == "Male") checked = "" @endif> पुरुष  <br>
                                
                        जन्म मिति : <strong class="dotted"> {{ date('d/m/Y', strtotime($child->dob_np)) }} </strong> ( ग/ म / सा ) जन्मिदाको तौल : <strong class="dotted"> {{ $child->weight }} </strong> ग्राम  <br>
                                
                        आमाको नाम : <strong class="dotted"> {{ $child->mother_name }} </strong> बाबुको नाम : <strong class="dotted"> {{ $child->father_name }} </strong><br>

                        ठेगाना : जिल्ला  <strong class="dotted">  </strong>  नं. पा.  / गा.वि.स. <strong class="dotted">  </strong> <br>
                        
                        वार्ड नं. <strong class="dotted"> {{ $child->ward_no }} </strong> गाउँ / टोल : <strong class="dotted"> {{ $child->tole }} </strong> <br>

                         स्वास्थ्य संस्थाको नाम : <strong class="dotted"> {{ $child->gethpName($child->hp_code) }} </strong> <br>

                        स्वास्थ्यकर्मीको नाम : <strong class="dotted">  </strong> सम्पर्क नं. <strong class="dotted">  </strong> <br>

                        कार्ड जारी  गरेको मिति : <strong class="dotted"> {{ \Carbon\Carbon::parse($child->created_at)->format('d/m/Y') }} </strong> ( ग/ म / सा )      
                    </p>
                </div>

                <h3 class="text-center"> खोप लगाएको विवरण </h3>
                    <div class="table-responsive"> 
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>खोपको नाम</th>
                                    <th>जन्मने बित्तिकै </th>
                                    <th>६ हप्ता </th>
                                    <th>१० हप्ता </th>
                                    <th>१४ हप्ता </th>
                                    <th>९ महिना </th>
                                    <th>१२ महिना </th>
                                    <th>१५ महिना </th>
                                </tr>
                                <tr>
                                    <th>बि.सि.जी.</th> 
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('BCG')->period('Birth')->first())
                                            {{ $data->vaccinated_date_np }}
                                        @endif
                                    </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                </tr>

                                <tr>
                                    <th>डि.पि.टि. हेपबी-हिव</th>
                                    <td class="td">  </td>
                                        <td>
                                            @if ($data = $child->vaccinations()->active()->vaccineName('Pentavalent')->period('6W')->first())
                                                {{ $data->vaccinated_date_np }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data = $child->vaccinations()->active()->vaccineName('Pentavalent')->period('10W')->first())
                                            {{ $data->vaccinated_date_np }}
                                        @endif                                        
                                        </td>
                                        <td>
                                            @if ($data = $child->vaccinations()->active()->vaccineName('Pentavalent')->period('14W')->first())
                                            {{ $data->vaccinated_date_np }}
                                        @endif                                        
                                        </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                </tr>

                                <tr>
                                   <th>ओ.पि.भी.</th>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('OPV')->period('6W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('OPV')->period('10W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('OPV')->period('14W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                </tr>

                                <tr>
                                    <th>पि.सि.भी.</th>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('PCV')->period('6W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('PCV')->period('10W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('PCV')->period('9M')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                </tr>

                                <tr>
                                    <th> एफ.आइ.पि.भी.</th>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('FIPV')->period('6W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('FIPV')->period('14W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                </tr>
                                <tr>
                                    <th>आर.भी</th>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('RV')->period('6W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('RV')->period('10W')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                </tr>
                                <tr>
                                    <th>दादुरा-रुवेला</th>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('MR')->period('9M')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('MR')->period('15M')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                </tr>

                                <tr>
                                    <th>जे.ई.</th>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td class="td">  </td>
                                    <td>
                                        @if ($data = $child->vaccinations()->active()->vaccineName('JE')->period('12M')->first())
                                        {{ $data->vaccinated_date_np }}
                                    @endif                                        
                                    </td>
                                    <td class="td">  </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <div id="chart">
                        {!! Charts::styles() !!}
					        <div class="app">
					                {!! $chart->html() !!}
					        </div>
					        <!-- End Of Main Application -->
					        {!! Charts::scripts() !!}
					        {!! $chart->script() !!}
                </div>
                <div id="aefis">
                    <h4 class="text-center"> ए.ई.एफ.आई.को विवरण </h4>
                    <div class="table-responsive"> 
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>खोपको नाम</th>
                                    <th>खोप लिएको मिति</th>
                                    <th>मूख्य लक्षण</th>
                                    <th>कैफियत</th>
                                </tr>
                                @if(count($child->aefisBaby)>0)
                                    @foreach($child->aefisBaby as $aefi)
                                    <tr>
                                        <th>{{ $aefi->vaccine or '' }}</th>
                                        <td>{{ $aefi->vaccinated_date or ''}}</td>
                                        <td>{{ $aefi->problem or ''}}</td>
                                        <td>{{ $aefi->advice or '' }}</td>
                                    </tr>
                                    @endforeach
                                @else  
                                <tr>
                                    <th></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection