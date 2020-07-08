@if (Session::get('error'))
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
@endif
@if (Session::get('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
        <br>
        <a href="{{ env('HMIS_BASE_URL') }}" target="_blank"> कृपया डाटा हेर्न HMIS ( DHIS 2 ) मा जानुहोस अथवा यहाँ क्लिक गर्नुहोस् </a>
    </div>
@endif