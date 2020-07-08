@extends('layouts.backend.app')

@section('content')

    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        सुरक्षित मातृत्व कार्क्रम
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        @include('reports.layouts.filter')
                        <div class="clearfix"></div>
                        
                        @include('reports.session')
                        <div class="clearfix"></div>
                        <style type="text/css">
                            body{margin:0;padding:0;}
                            table, td, th {border: 1px solid black; border-collapse: collapse;}
                            th{font-size: 9px; margin: 0;padding:0 10px 0 10px; background-color:#dfdfdf;}
                            td{font-size: 10px;padding:0 10px 0 10px;color:#000;}
                    
                            .rotated-parent {max-width: 40px; overflow: hidden;}
                            .rotated {
                                /* display: block; */
                                position: relative;
                                left: 0;
                                top: 0;
                                text-align: center;
                                white-space: nowrap !important;
                                float: left;		    
                                min-width: 400%;
                                margin-left: -150%;		    
                                /* padding: 5px 10px; */
                            }
                    
                            .rotated-up {
                                -ms-transform: rotate(-90deg);
                                -moz-transform: rotate(-90deg);
                                -webkit-transform: rotate(-90deg);
                                transform: rotate(-90deg);
                                filter: none; /* Mandatory for IE9 to show the vertical text correctly */ 
                                
                                -ms-transform-origin: center center 0;
                                -moz-transform-origin: center center 0;
                                -webkit-transform-origin: center center  0;
                                transform-origin: center center 0;
                            }
                            
                            .table-padding{
                            margin-top: 30px;
                        }
                        </style>
                        <div class="print" id="printable">
                        
                            <div id="print-header">
                                @include('reports.layouts.header-for-print')
                            </div>
                            <div style="float: right; text-align: right; font-size: 12px; margin-right:4%;">HMIS 9.3
                            </div>
                            <div class="clearfix"></div>
                        @foreach ($data as $woman)
                    
                        <table class="table table-bordered">
                            <tr>
                                <th rowspan="2">क्र सं</th>
                                <th rowspan="2">दर्ता नं.</th>
                                <th rowspan="2">महिलाको नाम र थर</th>
                                <th rowspan="2" colspan="2" width="20">जात / जाति कोड, उमेर र सम्पर्क नम्बर</th>
                                <th rowspan="2" width="5%">ठेगाना (जिल्ला / नगा. बि स./न.पा)</th>
                                <th rowspan="2">पतिको नाम, थर</th>
                                <th rowspan="2" colspan="2">Gravida Para LMP & EDD</th>
                                <th rowspan="2" colspan="6">गर्भवति जाँच (ANC) विवरण</th>
                                <th rowspan="2" colspan="4">HIV र Syphils परिक्षण</th>
                                <th colspan="8" class="text-center">गर्भ, प्रसुति र सुत्केरिको जटिलता</th>
                                <th rowspan="2" width="5%">गर्भवतिको जाँचको बेलामा दिएको उपचार/सल्लाह</th>
                            </tr>
                            <tr>						
                                <th>जटिलताहरु</th>
                                <th>गर्भवति जाँच </th>
                                <th>महिना</th>
                                <th>प्रसुति</th>
                                <th>महिना</th>
                                <th>सुत्केरि</th>
                                <th>महिना</th>
                                <th>अन्य जटिलता</th>						
                            </tr>
                            <tr>
                                <th>१</th>
                                <th>२</th>
                                <th>३</th>
                                <th colspan="2">४</th>
                                <th>५</th>
                                <th>६</th>
                                <th colspan="2">७</th>
                                <th colspan="6" align="center">८</th>
                                <th colspan="4" align="center">९</th>
                                <th>१०</th>
                                <th>११</th>
                                <th>१२</th>
                                <th>१३</th>
                                <th>१४</th>
                                <th>१५</th>
                                <th>१६</th>
                                <th>१७</th>	
                                <th>१८</th>					
                            </tr>
                            <tr>
                                <td rowspan="20">&nbsp;</td>
                                <td bgcolor="#dfdfdf">मुल दर्ता नं.</td>
                                <td rowspan="20">&nbsp;</td>
                                <td bgcolor="#dfdfdf">जाति कोड</td>
                                <td>&nbsp;</td>
                                <td rowspan="20">&nbsp;</td>
                                <td rowspan="20">&nbsp;</td>
                                <td colspan="2" align="center" bgcolor="#dfdfdf">Gravida</td>
                                <td colspan="2" bgcolor="#dfdfdf">गर्भवति जाँच</td>
                                <td bgcolor="#dfdfdf">गते</td>
                                <td bgcolor="#dfdfdf">महिना</td>
                                <td bgcolor="#dfdfdf">साल</td>
                                <td bgcolor="#dfdfdf">अाइरन फोलिक</td>
                                <td colspan="3" bgcolor="#dfdfdf">Counseling</td>
                                <td>1</td>
                                <td bgcolor="#dfdfdf">Ectopic pregnancy</td>
                                <td>1</td>
                                <td>म</td>
                                <td>1</td>
                                <td>म</td>
                                <td colspan="2" rowspan="2" >&nbsp;</td>
                                <td rowspan="20">&nbsp;</td>
                                <td rowspan="20">&nbsp;</td>
                            </tr>
                    
                            <tr>
                                <td>{{ $woman->mool_darta_no }}</td>
                                <td colspan="2" align="center" bgcolor="#dfdfdf">उमेर</td>
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" bgcolor="#dfdfdf">पहिलो भेट</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td bgcolor="#888">&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">HIV testing</td>
                                <td>2</td>
                                <td bgcolor="#dfdfdf">Abortion complication</td>
                                <td>2</td>
                                <td>म</td>
                                <td bgcolor="#888">&nbsp;</td>
                                <td bgcolor="#888">&nbsp;</td>
                            </tr>
                    
                            <tr>
                                <td bgcolor="#dfdfdf">सेवा दर्ता नं.</td>
                                <td><२०</td>
                                <td>&nbsp;</td>
                                <td colspan="2" align="center" bgcolor="#dfdfdf">Para</td>
                                <td colspan="2" bgcolor="#dfdfdf">चाैथाे महिना</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>&nbsp;</td>
                                <td bgcolor="#dfdfdf">Test date</td>
                                <td>DD</td>
                                <td>MM</td>
                                <td>YY</td>
                                <td bgcolor="#dfdfdf">Hypertension</td>
                                <td>3</td>
                                <td>म</td>
                                <td>3</td>
                                <td>म</td>
                                <td>3</td>
                                <td>म</td>
                            </tr>
                    
                            <tr>
                                <td rowspan="20">&nbsp;</td>
                                <td>≥२०</td>
                                <td>&nbsp;</td>
                                <td colspan="2">&nbsp;</td>
                                <td colspan="2" bgcolor="#dfdfdf">पाचाै महिना</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">Results received</td>
                                <td>3</td>
                                <td bgcolor="#dfdfdf">Severe/Pre-eclampsia</td>
                                <td>4</td>
                                <td>म</td>
                                <td>4</td>
                                <td>म</td>
                                <td>4</td>
                                <td>म</td>
                            </tr>
                    
                            <tr>
                                <td rowspan="20" class="rotated-parent"><div class="rotated rotated-up">सम्पर्क फोन नं.</div></td>
                                <td rowspan="20">9844225588</td>
                                <td rowspan="3" bgcolor="#dfdfdf">LMP</td>
                                <td rowspan="3" bgcolor="#dfdfdf">EDO</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">छैठाै महिना</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>&nbsp;</td>
                                <td colspan="2" bgcolor="#dfdfdf">HIV  Status</td>
                                <td>&nbsp;</td>
                                <td>4</td>
                                <td bgcolor="#dfdfdf">Eclampsia</td>
                                <td>5</td>
                                <td>म</td>
                                <td>5</td>
                                <td>म</td>
                                <td>5</td>
                                <td>म</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">साताै महिना</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>&nbsp;</td>
                                <td colspan="2" bgcolor="#dfdfdf">Partner HIV status known</td>
                                <td>&nbsp;</td>
                                <td>5</td>
                                <td bgcolor="#dfdfdf">Hyp.gravidarum</td>
                                <td>6</td>
                                <td>म</td>
                                <td>6</td>
                                <td>म</td>
                                <td>6</td>
                                <td>म</td>
                            </tr>
                    
                            <tr>
                                <td rowspan="3">ग</td>
                                <td rowspan="3">ग</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">अाठाै महिना</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">Partner referred</td>
                                <td>6</td>
                                <td bgcolor="#dfdfdf">APH</td>
                                <td>7</td>
                                <td>म</td>
                                <td bgcolor="#888">&nbsp;</td>
                                <td bgcolor="#888">&nbsp;</td>
                                <td colspan="2" rowspan="4" bgcolor="#888">&nbsp;</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">नवाै महिना</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">Syphills tested</td>
                                <td>7</td>
                                <td bgcolor="#dfdfdf">Prolnged labor</td>
                                <td colspan="2" rowspan="3" bgcolor="#888">&nbsp;</td>
                                <td>8</td>
                                <td>म</td>
                    
                            </tr>
                    
                            <tr>
                                <td rowspan="3">म</td>
                                <td rowspan="3">म</td>					
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">४ पटक (प्राेटाेकला)</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>	
                                <td bgcolor="#dfdfdf">जम्मा</td>
                                <td colspan="3" bgcolor="#dfdfdf">Syphills positive</td>
                                <td>8</td>
                                <td bgcolor="#dfdfdf">Obstruced labor</td>
                                <td>9</td>
                                <td>म</td>
                                    
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">जुकाको अाैषधी</td>	
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td rowspan="6" bgcolor="#888">&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">Syphills treated</td>
                                <td>9</td>
                                <td bgcolor="#dfdfdf">Ruptured uterus</td>
                                <td>10</td>
                                <td>म</td>
                                <td>10</td>
                                <td>म</td>
                                <td>10</td>
                                <td>म</td>
                            </tr>
                    
                            <tr>
                                <td rowspan="4">सा</td>
                                <td rowspan="4">सा</td>					
                            </tr>
                    
                            <tr>
                                <td rowspan="3" bgcolor="#dfdfdf">टिडि</td>
                                <td bgcolor="#dfdfdf">१</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>	
                                <td colspan="3" bgcolor="#dfdfdf">ART started</td>
                                <td>10</td>
                                <td bgcolor="#dfdfdf">PPH</td>
                                <td colspan="2" rowspan="3" bgcolor="#888">&nbsp;</td>	
                                <td bgcolor="#888">&nbsp;</td>
                                <td bgcolor="#888">&nbsp;</td>
                                <td>11</td>
                                <td>म</td>							
                            </tr>
                    
                            <tr>
                                <td bgcolor="#dfdfdf">२</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td rowspan="2" bgcolor="#dfdfdf">Start date</td>
                                <td rowspan="2">DD</td>
                                <td rowspan="2">MM</td>
                                <td rowspan="2">YY</td>
                                <td bgcolor="#dfdfdf">Retained placenta</td>
                                <td>12</td>
                                <td>म</td>
                                <td>12</td>
                                <td>म</td>
                            </tr>
                    
                            <tr>
                                <td bgcolor="#dfdfdf">२‍‌+</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td bgcolor="#dfdfdf">Pueperal sepsis</td>
                                <td>13</td>
                                <td>म</td>
                                <td>13</td>
                                <td>म</td>
                            </tr>
                        </table>
                    
                        <table class="table table-padding table-bordered">
                            <tr>
                                <th rowspan="2" colspan="3">भर्ना गर्दाको मिति, समय र अवस्था</th>
                                <th rowspan="2" colspan="3">प्रसुति गर्दाको मिति, समय र स्थान</th>
                                <th rowspan="2" colspan="2" width="9%">बच्चाको अवस्थित, प्रसुतिको प्रकार र प्रसुती गराउने स्वास्थ्यकर्मी</th>
                                <th rowspan="2" width="50">मुख्य प्रसुती गराउने स्वास्थ्यकर्मी</th>
                                <th rowspan="2" width="5%">प्रसुती बेलामा दिएको उपचार/सल्लाह</th>
                                <th rowspan="2" colspan="3">नवशिशुको अवस्था</th>
                                <th rowspan="2">नवशिशुलाई दिएको उपचार/सल्लाह</th>
                                <th rowspan="2" colspan="4">सुत्केरि जाँच (PNC) को विवरण</th>
                                <th rowspan="2" colspan="2">रगत दिएको अबस्था र परिणाम</th>
                                <th rowspan="2">सुत्केरिको बेलामा दिएको उपचार/चरण</th>
                                <th colspan="3">डिस्चार्ज</th>
                                <th rowspan="2" colspan="3">मातृ र नवशिशुको मित र कारण</th>
                                <th rowspan="2" colspan="2" width="8%">गर्भवती जाँच र यातायात खर्च</th>
                                <th rowspan="2">कैफियत</th>
                            </tr>
                        
                            <tr>
                                <th colspan="3">डिस्चार्ज गरेको मिति, समय, अामाको अवस्था</th>				
                            </tr>
                    
                            <tr>
                                <th colspan="3">१९</th>
                                <th colspan="3">२०</th>
                                <th colspan="2">२१</th>
                                <th>२२</th>
                                <th>२३</th>
                                <th colspan="3">२४</th>
                                <th>२५</th>
                                <th colspan="4">२६</th>
                                <th colspan="2">२७</th>
                                <th>२८</th>
                                <th colspan="3">२९</th>
                                <th colspan="3">३०</th>
                                <th colspan="2">३१</th>
                                <th>३२</th>				
                            </tr>
                    
                            <tr>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">मिति</td>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">मिति</td>
                                <td colspan="2" bgcolor="#dfdfdf">बच्चाको अवस्था</td>
                                <td rowspan="14">&nbsp;</td>
                                <td rowspan="14">&nbsp;</td>
                                <td bgcolor="#dfdfdf">जीवित</td>
                                <td bgcolor="#dfdfdf">छाेरि</td>
                                <td bgcolor="#dfdfdf">छाेरा</td>
                                <td rowspan="14">&nbsp;</td>
                                <td bgcolor="#dfdfdf">सुत्केरी जाँच</td>
                                <td bgcolor="#dfdfdf">गते</td>
                                <td bgcolor="#dfdfdf">महिना</td>
                                <td bgcolor="#dfdfdf">साल</td>
                                <td bgcolor="#dfdfdf">अवस्था</td>
                                <td bgcolor="#dfdfdf">परिणाम</td>
                                <td rowspan="14">&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">मिति</td>
                                <td colspan="3" bgcolor="#dfdfdf">मातृ मृत्यु</td>
                                <td colspan="2" bgcolor="#dfdfdf">गर्भवती उत्प्रेरणा खर्च</td>
                                <td rowspan="14">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td bgcolor="#dfdfdf">Cephalic</td>
                                <td>1</td>
                                <td bgcolor="#dfdfdf">संख्या</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td bgcolor="#dfdfdf">प्रथम पटक (जन्मेको २४ घण्टा भित्र)</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td bgcolor="#dfdfdf">गर्भवती</td>
                                <td>&nbsp;</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td bgcolor="#dfdfdf">पाएको</td>
                                <td>1</td>
                            </tr>
                    
                            <tr>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">समय</td>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">समय</td>
                                <td bgcolor="#dfdfdf">Breech</td>
                                <td>2</td>
                                <td rowspan="2" bgcolor="#dfdfdf">ताैल <br>(ग्राममा)</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td bgcolor="#dfdfdf">दाेस्राे पटक (जन्मेको ३ दिन भित्र)</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>ग</td>
                                <td>म</td>
                                <td align="center" colspan="3" bgcolor="#dfdfdf">समय</td>
                                <td align="center" colspan="3" bgcolor="#dfdfdf">कारण</td>
                                <td bgcolor="#dfdfdf">नपाएको</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td colspan="2">AM</td>
                                <td>PM</td>
                                <td colspan="2">AM</td>
                                <td>PM</td>
                                <td bgcolor="#dfdfdf">Shoulder</td>
                                <td>3</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td bgcolor="#dfdfdf">तेस्राे पटक (जन्मेको ७ दिन भित्र)</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td bgcolor="#dfdfdf">प्रसुती</td>
                                <td>&nbsp;</td>
                                <td colspan="2">AM</td>
                                <td>PM</td>
                                <td colspan="3" rowspan="3">&nbsp;</td>
                                <td colspan="2" rowspan="2" bgcolor="#dfdfdf">यातायात खर्च</td>
                            </tr>
                    
                            <tr>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">अवस्था र स्थिती</td>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">स्थान</td>
                                <td colspan="2" align="center" bgcolor="#dfdfdf">प्रसुतीको प्रकार</td>
                                <td colspan="2" bgcolor="#dfdfdf">नाभी मलमको प्रयोग</td>
                                <td>१</td>
                                <td bgcolor="#dfdfdf">थप पटक</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td>ग</td>
                                <td>म</td>
                                <td colspan="2" bgcolor="#dfdfdf">बसेको अवधि</td>
                                <td>दिन</td>
                            </tr>
                    
                            <tr>
                                <td colspan="3" rowspan="20">&nbsp;</td>
                                <td colspan="2" bgcolor="#dfdfdf">यस संस्था</td>
                                <td>१</td>
                                <td bgcolor="#dfdfdf">Spontaneous</td>
                                <td>1</td>
                                <td rowspan="6" bgcolor="#dfdfdf">नवशिशुको अवस्था</td>
                                <td bgcolor="#dfdfdf">Normal</td>
                                <td>1</td>
                                <td bgcolor="#dfdfdf">प्राेटाेकल अनुसार ३ पटक</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>	
                                <td bgcolor="#dfdfdf">सुत्केरी</td>
                                <td>&nbsp;</td>
                                <td colspan="3" bgcolor="#dfdfdf">आमाको अवस्था</td>
                                <td bgcolor="#dfdfdf">पाएको</td>
                                <td>१</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">अरु संस्था</td>
                                <td>२</td>
                                <td bgcolor="#dfdfdf">Vacuum</td>
                                <td>2</td>
                                <td bgcolor="#dfdfdf">Asphyxiated</td>
                                <td>2</td>
                                <td colspan="4" bgcolor="#888">&nbsp;</td>
                                <td>ग</td>
                                <td>म</td>
                                <td colspan="2" bgcolor="#dfdfdf">Recovered</td>
                                <td>1</td>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">नवशिशु मृत्यु</td>
                                <td bgcolor="#dfdfdf">नपाएको</td>
                                <td>2</td>
                            </tr>
                    
                            <tr>
                                <td colspan="3" rowspan="5" align="center">स्वास्थ्य संस्थाको नाम</td>
                                <td bgcolor="#dfdfdf">Forceps</td>
                                <td>3</td>
                                <td bgcolor="#dfdfdf">Hypothermia</td>
                                <td>3</td>
                                <td bgcolor="#dfdfdf">भिटामिन ए</td>
                                <td>DD</td>
                                <td>MM</td>
                                <td>YY</td>
                                <td rowspan="7" bgcolor="#dfdfdf">जम्मा</td>
                                <td rowspan="7">&nbsp;</td>
                                <td colspan="2" bgcolor="#dfdfdf">Not Improved</td>
                                <td>2</td>
                                <td>ग</td>
                                <td>म</td>
                                <td>सा</td>
                                <td colspan="2" bgcolor="#dfdfdf">नपाएको भए कारण</td>
                            </tr>
                    
                            <tr>
                                <td bgcolor="#dfdfdf">CS</td>
                                <td>4</td>
                                <td bgcolor="#dfdfdf">Jaundice</td>
                                <td>4</td>
                                <td bgcolor="#dfdfdf">४५ आयइरन फाेलिक यसिड चक्कि</td>
                                <td>DD</td>
                                <td>MM</td>
                                <td>YY</td>
                                <td colspan="2" bgcolor="#dfdfdf">Referred out</td>
                                <td>3</td>
                                <td colspan="3" align="center" bgcolor="#dfdfdf">कारण</td>
                                <td colspan="2" rowspan="6">&nbsp;</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" align="center" bgcolor="#dfdfdf">प्रसुती गराउने स्वास्थ्यकर्मी</td>
                                <td colspan="2" rowspan="2" bgcolor="#dfdfdf">Congenital anomalies</td>
                                <td rowspan="5" bgcolor="#dfdfdf">प्रसुती पछिको परिवार नियोजन</td>
                                <td bgcolor="#dfdfdf">TSA</td>
                                <td bgcolor="#dfdfdf">TLA</td>
                                <td bgcolor="#dfdfdf">Perm</td>
                                <td colspan="2" bgcolor="#dfdfdf">LAMA</td>
                                <td>4</td>
                                <td colspan="3" rowspan="5">&nbsp;</td>
                            </tr>
                    
                            <tr>
                                <td bgcolor="#dfdfdf">दक्ष प्रसुतिकर्मी</td>
                                <td>१</td>
                                <td rowspan="4">1</td>
                                <td rowspan="4">2</td>
                                <td rowspan="4">3</td>
                                <td colspan="2" bgcolor="#dfdfdf">Absconded</td>
                                <td>5</td>
                            </tr>
                    
                            <tr>
                                <td rowspan="3" bgcolor="#dfdfdf">दक्ष प्रसुतीकर्मी बाहेक अन्य</td>
                                <td rowspan="3">2</td>
                                <td rowspan="3" bgcolor="#dfdfdf">मतृ जन्म (संख्या)</td>			
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">घर</td>
                                <td>३</td>
                                <td bgcolor="#dfdfdf">Fresh</td>
                                <td>&nbsp;</td>
                                <td colspan="2" rowspan="2" bgcolor="#dfdfdf">Died</td>
                                <td rowspan="2">6</td>
                            </tr>
                    
                            <tr>
                                <td colspan="2" bgcolor="#dfdfdf">अरु स्थान</td>
                                <td>४</td>
                                <td bgcolor="#dfdfdf">Macerated</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>     
                        @endforeach
                                               
                            
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
@endsection
