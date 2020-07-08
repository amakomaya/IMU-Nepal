@component('mail::message')
## महिला स्वयं मूल्यांकन रिपोर्ट
@component('mail::panel')
महिलाको नाम : {{ $woman['name'] }} <br>
फोन : {{ $woman['phone'] }} <br>
अन्तिम महिनावारीको मिति : {{ $woman['lmp_date_np'] }} <br>
उमेर : @if($woman['age'] !== 0) {{ $woman['age'] }} @endif<br>
महिलाको अबस्था : @if(array_key_exists('symptoms_type', $data)) @if($data['symptoms_type'] == 2)  प्रसूति  @elseif($data['symptoms_type'] == 3) सुत्केरी @else गर्भावस्था  @endif @else गर्भावस्था @endif <br>
जिल्ला : {{ (\App\Models\District::where('id',$woman['district_id'])->first()) ? \App\Models\District::where('id',$woman['district_id'])->first()->district_name : ''  }} <br>
स्थानीय सरकार : {{ (\App\Models\Municipality::where('id',$woman['municipality_id'])->first()) ? \App\Models\Municipality::where('id',$woman['municipality_id'])->first()->municipality_name : ''  }} <br>
वडा / टोल : {{ $woman['ward'] ?? '' }}, {{ $woman['tole'] ?? ''}} <br>
@php 
    $latitude = $data['latitude'] ?? '';
    $longitude = $data['longitude'] ?? '';
    $link = "http://maps.google.com/maps?q=".$latitude.",".$longitude;
@endphp
@if(!empty($longitude) and !empty($latitude))
महिलाको स्थान (Location) : [Go To Map]({{ $link }}) or Copy and Paste Link <br> [{{ $link }}]({{ $link }})

@endif
@endcomponent
@component('mail::table')
@if(array_key_exists('symptoms_type', $data))
@if($data['symptoms_type'] == 1) 
| लक्षणहरू     | स्थिति / अवस्था         | 
| ------------- |:-------------:| 
| अलि धेरै नै टाउको दुख्ने गरेको      | @if($data['headache']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| योनीबाट रगत बगेको      | @if($data['vagina_bleed']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| हात खुट्टा अररो भई काँप छुटेको वा मुर्छा परेको      | @if($data['tremble_or_faint']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| आखाँ धमिलो गरि देख्ने गरेको     | @if($data['eyes_blur']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| गर्भवस्थाको पहिलो महिनामा नै पेट धेरै दुख्ने गरेको      | @if($data['abdominal_pain']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| १००.४ डिग्री भन्दा माथि ज्वरो आएको      | @if($data['fever_hundred']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| स्वास फेर्न गार्हो      | @if($data['difficult_breathe']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| रूघा खोकि लागेको      | @if($data['cough_and_cold']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |

@elseif($data['symptoms_type'] == 2) 
| लक्षणहरू     | स्थिति / अवस्था         | 
| ------------- |:-------------:| 
| ८ घण्टा भन्दा लामो सुत्केरी ब्यथा लागेको    | @if($data['labor_pain']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| पहिलो हात, खुट्टा वा नाल निस्केको    | @if($data['protusion']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| हात खुट्टा अररो भई काँप छुटेको वा मुर्छा परेको    | @if($data['tremble_or_faint']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| बच्चा जन्माउनु अघि अथवा बच्चा जन्मीसकेपछि पनि धेरै रगत बगेको    | @if($data['before_or_after_birth_bleed']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| १००.४ डिग्री भन्दा माथि ज्वरो आएको    | @if($data['fever_hundred']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| स्वास फेर्न गार्हो भएको वा छाती दुखेको    | @if($data['difficult_breathe_chest_pain']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| रूघा खोकि लागेको    | @if($data['cough_and_cold']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |

@elseif($data['symptoms_type'] == 3) 
| लक्षणहरू     | स्थिति / अवस्था         | 
| ------------- |:-------------:| 
| अलि धेरै नै टाउको दुख्ने गरेको      | @if($data['headache']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| योनीबाट रगत बगेको      | @if($data['vagina_bleed']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| हात खुट्टा अररो भई काँप छुटेको वा मुर्छा परेको      | @if($data['tremble_or_faint']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| योनीबाट गन्हाउने पानी बगेमा वा तल्लो पेट दुखेको      | @if($data['smelling_water_or_abdomen_pain']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| १००.४ डिग्री भन्दा माथि ज्वरो आएको    | @if($data['fever_hundred']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| स्वास फेर्न गार्हो भएको वा छाती दुखेको    | @if($data['difficult_breathe_or_chest_pain']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| रूघा खोकि लागेको    | @if($data['cough_and_cold']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |

@endif
@else
| लक्षणहरू     | स्थिति / अवस्था         | 
| ------------- |:-------------:| 
| योनीबाट रगत बगेको      | @if($data['vagina_bleed']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| शरीरमा कम्पन आएको      | @if($data['vibration_body']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| बेहोस हुने वा मुर्छा हुने      | @if($data['faint']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| अलि धेरै नै टाउको दुख्ने गरेको      | @if($data['headache']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| आखाँ धमिलो गरि देख्ने गरेको      | @if($data['eyes_blur']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| हात, गोडा, अनि मुख सुनिन्ने गरेको      | @if($data['swollen']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| गर्भवस्थाको पहिलो महिनामा नै पेट धेरै दुख्ने गरेको      | @if($data['abdominal_pain']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| गर्भवस्थाको २२ हप्ता वा साढे ५ महिना पनि बच्चा चलेको थाहा      | @if($data['baby_movement']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
|   ज्वरो आउने    | @if($data['fever']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| स्वास फेर्न गार्हो      | @if($data['difficult_breathe']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif |
| लगातार वाकवाकि लाग्नु वा उल्टि      | @if($data['nausea']==1) <input type="checkbox" checked="checked" disabled> <strong>छ</strong> @else  <input type="checkbox" disabled> छैन @endif | 
@endif
@endcomponent
@if(array_key_exists('other_problems', $data))
@if(!empty($data['other_problems']))
अन्य समस्याहरू : {{ $data['other_problems'] }}
@endif
@endif
@endcomponent