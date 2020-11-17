<h2>Add a new message</h2>
<form action="{{ route('messages.update', $thread->id) }}" method="post">
{{ method_field('put') }}
{{ csrf_field() }}

<!-- Message Form Input -->
    <div class="form-group">
{{--        <textarea name="message" class="form-control">{{ old('message') }}</textarea>--}}
        {!! rcForm::ckeditor('Message', 'message') !!}

    </div>

    <input type="checkbox" class="toggle-checkbox" onClick="toggle(this)" /><span> Select All</span><br/><br>
    @if($main_users->count() > 0)
        <label>Contact IMU Nepal technical support team</label>
        <div class="row">
            <div class="checkbox">
                @foreach($main_users as $user)
                    <div class="col-lg-4">
                        <label title="IMU Nepal support team">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">IMU Nepal support team
                            <br>
                            Lazimpat, Kathmandu <br>
                            Email : imucovidnepal@gmail.com <br>
                            Phone No : 01-4428090
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div>
        </div>
    @endif
    @if($center_users->count() > 0)
        <label>Center Recipients</label>
        <div class="row">
            <div class="checkbox">
                @foreach($center_users as $user)
                    <div class="col-lg-4">
                        <label title="{{ $user->username }}">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">{!!$user->username!!}
                            <br>
                            {!! $user->getUserFullInformation($user) !!}
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div>
        </div>
    @endif
    @if($province_users->count() > 0)
        <label>Province Recipients</label>
        <div class="row">
            <div class="checkbox">
                @foreach($province_users as $user)
                    <div class="col-lg-4">
                        <label title="{{ $user->username }}">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">{!!$user->username!!}
                            <br>
                            {!! $user->getUserFullInformation($user) !!}
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div>
        </div>
    @endif
    @if($district_users->count() > 0)
        <label>District Recipients</label>
        <div class="row">

            <div class="checkbox">
                @foreach($district_users as $user)
                    <div class="col-lg-4">
                        <label title="{{ $user->username }}">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">{!!$user->username!!}
                            <br>
                            {!! $user->getUserFullInformation($user) !!}
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div></div>
    @endif
    @if($municipality_users->count() > 0)
        <label>Municipality Recipients</label>
        <div class="row">

            <div class="checkbox">
                @foreach($municipality_users as $user)
                    <div class="col-lg-4">
                        <label title="{{ $user->username }}">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">{!!$user->username!!}
                            <br>
                            {!! $user->getUserFullInformation($user) !!}
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div></div>
    @endif
    @if($hospital_or_cict_users->count() > 0)

        <label>Hospitals or CICT Team Recipients</label>
        <div class="row">

            <div class="checkbox">
                @foreach($hospital_or_cict_users as $user)
                    <div class="col-lg-4">
                        <label title="{{ $user->username }}">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">{!!$user->username!!}
                            <br>
                            {!! $user->getUserFullInformation($user) !!}
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div></div>
    @endif

    @if($lab_users->count() > 0)
        <label>Lab Recipients</label>
        <div class="row">

            <div class="checkbox">
                @foreach($lab_users as $user)
                    <div class="col-lg-4">
                        <label title="{{ $user->username }}">
                            <input type="checkbox" name="recipients[]"
                                   value="{{ $user->id }}">{!!$user->username!!}
                            <br>
                            {!! $user->getUserFullInformation($user) !!}
                        </label>
                        <hr>
                    </div>

                @endforeach
            </div></div>
@endif
<!-- Submit Form Input -->
    <div class="form-group">
        <button type="submit" class="btn btn-primary form-control">Submit</button>
    </div>
</form>