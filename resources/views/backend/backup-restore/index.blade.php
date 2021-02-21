@extends('layouts.backend.app')
@section('style')
<style>
    .tabbable-panel {
    border:1px solid #eee;
    padding: 10px;
    }

    .tabbable-line > .nav-tabs {
    border: none;
    margin: 0px;
    }
    .nav-tabs li {
    margin: 0 5px;
    border-top: 1px solid #fff !important;
    }
    .tabbable-line > .nav-tabs > li {
    margin-right: 2px;
    }
    .tabbable-line > .nav-tabs > li > a {
    border: 0;
    margin-right: 0;
    color: #737373;
    }
    .tabbable-line > .nav-tabs > li > a > i {
    color: #a6a6a6;
    }
    .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
    border-bottom: 4px solid rgb(80,144,247);
    }
    .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
    border: 0;
    background: none !important;
    color: #333333;
    }
    .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
    color: #a6a6a6;
    }
    .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
    margin-top: 0px;
    }
    .tabbable-line > .nav-tabs > li.active {
    border-bottom: 4px solid #32465B;
    position: relative;
    }
    .tabbable-line > .nav-tabs > li.active > a {
    border: 0;
    color: #333333;
    }
    .tabbable-line > .nav-tabs > li.active > a > i {
    color: #404040;
    }
    .tabbable-line > .tab-content {
    margin-top: -3px;
    background-color: #fff;
    border: 0;
    border-top: 1px solid #eee;
    padding: 15px 0;
    }
    .portlet .tabbable-line > .tab-content {
    padding-bottom: 0;
    }
</style>
@endsection
@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Backup And Restore
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div><br/>
                                @endif
                                @if (Session::get('error'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                <div class="tabbable-panel">
                                    <div class="tabbable-line">
                                        <ul class="nav nav-tabs ">
                                            <li class="active">
                                                <a href="#tab_default_1" data-toggle="tab">
                                                    Register Only </a>
                                            </li>
                                            @if(auth()->user()->hasAnyDirectPermission(['vaccination'])
)
                                                <li>
                                                    <a href="#tab_default_2" data-toggle="tab">
                                                        Vaccinated Only </a>
                                                </li>
                                            @endif

                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_default_1">
                                                <br><br>
                                                {!! rcForm::open('POST', route('backup-restore.store')) !!}
                                                    <div class="form-group">
                                                        <input type="text" name="import_type" value="1" hidden>
                                                        <div class="input-group input-file" name="file_path">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default btn-choose" type="button">Choose</button>
                                                                </span>
                                                            <input type="text" class="form-control" name="file_path"
                                                                   placeholder='Choose a file...'/>
                                                        </div>
                                                    </div>
                                                <button class="btn btn-primary text-center"> Restore</button>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="tab_default_2">
                                                <br><br>
                                                {!! rcForm::open('POST', route('backup-restore.store')) !!}
                                                <div class="form-group">
                                                    <input type="text" name="import_type" value="2" hidden>
                                                    <div class="input-group input-file" name="file_path">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default btn-choose" type="button">Choose</button>
                                                                </span>
                                                        <input type="text" class="form-control" name="file_path"
                                                               placeholder='Choose a file...'/>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary text-center"> Restore</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
@endsection
@section('script')
    <script>
        function bs_input_file() {
            $(".input-file").before(
                function () {
                    if (!$(this).prev().hasClass('input-ghost')) {
                        var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                        element.attr("name", $(this).attr("name"));
                        element.change(function () {
                            element.next(element).find('input').val((element.val()).split('\\').pop());
                        });
                        $(this).find("button.btn-choose").click(function () {
                            element.click();
                        });
                        $(this).find('input').css("cursor", "pointer");
                        $(this).find('input').mousedown(function () {
                            $(this).parents('.input-file').prev().click();
                            return false;
                        });
                        return element;
                    }
                }
            );
        }

        $(function () {
            bs_input_file();
        });
    </script>
@endsection
