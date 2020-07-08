@extends('layouts.backend.app')
@section('style')
<style>
.activity-feed {
  padding: 15px;
}
.activity-feed .feed-item {
  position: relative;
  padding-bottom: 20px;
  padding-left: 30px;
  border-left: 2px solid #e4e8eb;
}
.activity-feed .feed-item:last-child {
  border-color: transparent;
}
.activity-feed .feed-item:after {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  left: -6px;
  width: 10px;
  height: 10px;
  border-radius: 6px;
  background: #fff;
  border: 1px solid #f37167;
}
.activity-feed .feed-item .date {
  position: relative;
  top: -5px;
  color: #8c96a3;
  text-transform: uppercase;
  font-size: 13px;
}
.activity-feed .feed-item .text {
  position: relative;
  top: -3px;
}
</style>
<link rel="stylesheet" rel="stylesheet" type="text/css" href="/libs/json-pretty/style.css">
<script src="/libs/json-pretty/script.js"></script>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h2>Activity Feed</h2>
                <hr>
                <div class="activity-feed">
                    @foreach ($logs as $key => $log)
                    @if ($log->log_name == 'login')          
                      <div class="feed-item">
                        <div class="date">{{ $log->created_at->diffForHumans() }}</div>
                            <div class="text">
                                <strong>{{ $log->properties['username'] }} </strong>is loged in. <br>
                                @php
                                    $description = $log->description;
                                @endphp
                                <a id="{{ $key }}-view-more" >View Details</a>
                                <pre id="{{ $key }}" style="display:none"></pre>
                                {{-- <textarea class="form-control" id="{{ $key }}" rows="10">{{ $description }}</textarea> --}}
                                <script>
                                  $('#{!! $key !!}').html(prettyPrintJson.toHtml({!! $description !!}));
                                  $("#{!! $key !!}-view-more").click(function() {
                                      $("#{!! $key !!}").toggle();
                                  });
                                </script>
                            </div>
                        </div>
                        <hr>
                        @else          
                          <div class="feed-item">
                          <div class="date">{{ $log->created_at->diffForHumans() }}</div>
                              <div class="text">
                                  Log Name : {{ $log->log_name }} <br>
                                  Description : {{ $log->description }}<br>
                                  Properties :  <a id="{{ $key }}-view-more" >View Details</a>
                                  <pre id="{{ $key }}" style="display:none"></pre>
                                  <script>
                                    $('#{!! $key !!}').html(prettyPrintJson.toHtml({!! $log->changes !!}));
                                    $("#{!! $key !!}-view-more").click(function() {
                                        $("#{!! $key !!}").toggle();
                                    });
                                  </script>
                              </div>
                          </div>
                          <hr>
                        @endif
                    @endforeach
                </div>
                <div class="pull-right">{!! $logs->render() !!}</div>
            </div>
        </div>
    </div>
@endsection