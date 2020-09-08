@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            @if (Request::session()->has('message'))
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    {!! Request::session()->get('message') !!}

                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as Institution Admin !
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row" style="padding: 15px;">
    <div class="panel panel-default col-lg-12">
        <div class="panel-body">
                {!! $chartWoman->html() !!}
        </div>
    </div>
    </div>
    <div class="panel-body">
        <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['situation_normal'] }}</div>
                        <div><h4>समान्य अवस्था</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['situation_possible'] }}</div>
                        <div><h4>सम्भाब्य जोखिम अवस्था</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['situation_danger'] }}</div>
                        <div><h4>जोखिम अवस्था</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    </div>
<div class="row" style="padding: 15px;">
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
{{--                         <div class="huge">{{ $data['infected'] }}</div> --}}
                    <div><h4>RDT +ve = {{ $data['rdt_positive'] }}, PCR +ve = {{ $data['pcr_positive'] }}, Both +ve = {{ $data['both_positive'] }}</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
   {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका संक्रमित</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका संक्रमित</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका संक्रमित</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div> --}}

    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['totalOrgQuarintine'] }}</div>
                        <div><h4>जम्मा संस्थागत क्वारेन्टाइनमा </h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका संस्थागत क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका संस्थागत क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका संस्थागत क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div> --}}

    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['totalOrgIsolation'] }}</div>
                        <div><h4>जम्मा संस्थागत आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका संस्थागत आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका संस्थागत आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका संस्थागत आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div> -- }}

    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['totalHomeQuarintine'] }}</div>
                        <div><h4>जम्मा होम क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका होम क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका होम क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका होम क्वारेन्टाइनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div> --}}

    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['totalHomeIsolation'] }}</div>
                        <div><h4>जम्मा होम आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका होम आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका होम आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका होम आइसोलेसनमा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div> --}}

    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['totalHealthInstitude'] }}</div>
                        <div><h4>जम्मा स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>--}}

    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{ $data['totalOther'] }}</div>
                        <div><h4>जम्मा अन्य</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    {{-- <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>यात्रा नगरेका स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>आन्तरिक यात्रा गरेका स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">100</div>
                        <div><h4>अन्तराष्ट्रिय यात्रा गरेका स्वास्थ्य संस्थामा</h4></div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>--}}
</div>
</div>
<!-- /#page-wrapper -->
{!! $chartWoman->script() !!}
{!! Charts::scripts() !!}
@endsection