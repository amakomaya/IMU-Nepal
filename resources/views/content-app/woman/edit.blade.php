
@extends('layouts.backend.app')
@section('style')
    <style type="text/css">
        label.error {
    color: red;
    font-size: 1rem;
    display: block;
    margin-top: 5px;
}

input.error {
    border: 1px dashed red;
    font-weight: 300;
    color: red;
}
    </style>
@endsection
@section('content')
    <div id="page-wrapper">
        <!-- add modal -->
                <div class="modal-body">
                    {!! rcForm::open('POST', route('content-app.reg.woman.update', $row->id), ['id'=>'update']) !!}
                    {{ method_field('PATCH') }}

                    <label><h3>Edit</h3></label><br>
                    <hr>
                    {!! rcForm::text('Name', 'name', $row->name, ['required' => 'required', 'minlength'=>"2"]) !!}
                    {!! rcForm::text('Phone No', 'phone', $row->phone, ['required' =>'required']) !!}
                    {!! rcForm::text('Age', 'age', $row->age, ['required' =>'required']) !!}
                    {!! rcForm::text('LMP Date EN', 'lmp_date_en', $row->lmp_date_en, ['required' =>'required']) !!}
                    {!! rcForm::text('LMP Date NP', 'lmp_date_np', $row->lmp_date_np, ['required' =>'required']) !!}
                    <div class="form-group">
                      <label>Select District</label>
                      <select class="form-control" name="district_id">
                        @foreach($districts as $item)
                            <option value="{{$item->id}}"
                                @if($item->id === $row->district_id) selected @endif
                                >{{$item->district_name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Select Municipalities</label>
                      <select class="form-control" name="municipality_id">
                        @foreach($municipalities as $item)
                            <option value="{{$item->id}}"
                                @if($item->id === $row->municipality_id) selected @endif
                                >{{$item->municipality_name}}</option>
                        @endforeach
                      </select>
                    </div>
                    {!! rcForm::text('Ward No', 'ward_no', $row->ward_no, ['required' =>'required']) !!}
                    {!! rcForm::text('Tole', 'tole', $row->tole, ['required' =>'required']) !!}

                    {!! rcForm::text('Username', 'username', $row->username) !!}
                    {!! rcForm::text('Password', 'password', '', ['data-validation'=> "[OPTIONAL, NAME, L>=2, TRIM]", 'data-toggle'=>"popover", 'data-content'=>"Name is optional"]) !!}
                    <div hidden>
                        {!! rcForm::text('', 'longitude', $row->longitude) !!}
                        {!! rcForm::text('', 'latitude', $row->latitude) !!}
                    </div>
                    <button class="btn btn-success btn-lg pull-right btn-block" onclick="return confirm('Are you sure you want to update this records ?');"><i class="fa fa-update-o" aria-hidden="true"></i> <strong>UPDATE</strong> </button>
                    </form>
    </div>

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $("#update").validate();

    });
</script>

@endsection
@endsection