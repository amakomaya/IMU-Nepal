@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

            <h1>Create a new message</h1>
    <form action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
{{--        <div class="col-md-6">--}}
            <!-- Subject Form Input -->
            <div class="form-group">
                <label class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}">
            </div>

            <!-- Message Form Input -->
            <div class="form-group">
{{--                <label class="control-label">Message</label>--}}
                {!! rcForm::ckeditor('Message', 'message') !!}
{{--                <textarea name="message" class="form-control">{{ old('message') }}</textarea>--}}
            </div>
            <div class="form-group">
{{--                <label class="control-label" for="document">Attachment</label>--}}
{{--                <input type="file" name="attachment">--}}
{{--                <div class="custom-file">--}}
{{--                    <label>Attachment file</label>--}}
{{--                    <input type="file" name="attachment" id="attachment">--}}
{{--                </div>--}}
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
{{--        </div>--}}
    </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script language="JavaScript">
        $( '.toggle-checkbox' ).click( function () {
            $( 'input[type="checkbox"]' ).prop('checked', this.checked)
        })
    </script>
@endsection