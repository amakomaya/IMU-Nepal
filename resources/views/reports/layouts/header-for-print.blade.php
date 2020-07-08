@php $months = array('Baishakh', 'Jestha', 'Ashad', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra') @endphp

@php
    use Carbon\Carbon;
    use Yagiten\Nepalicalendar\Calendar;

    if(empty($select_year)){
        $now = Carbon::now();
        $now_in_nepali = Calendar::eng_to_nep($now->year, $now->month,$now->day);
        $select_year = $now_in_nepali->getYear();
    }

    if(empty($select_month)){
        $now = Carbon::now();
        $now_in_nepali = Calendar::eng_to_nep($now->year, $now->month,$now->day);
        $select_month = $now_in_nepali->getMonth();
    }
@endphp
@php $month = $select_month; @endphp
@foreach($months as $key => $value)
    @if($key+1 == $select_month)
        @php $month = $value; @endphp
    @endif
@endforeach

<h5 class="text-left">
    @if(!empty($hp_code))
        स्वास्थ्य चौकी : {{ \App\Models\Healthpost::where('hp_code', $hp_code)->first()->name }}
    @else
        <h4 class="text-left">
            style="text-decoration: underline">{{ \App\Models\Municipality::where('id',$municipality_id)->first()->municipality_name }}</h4>
    @endif
    <h5> {{ $month }} , {{ $select_year }}</h5>
    <hr style="border: 1px solid;">
</h5>