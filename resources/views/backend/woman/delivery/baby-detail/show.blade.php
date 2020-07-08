                <div class="panel panel-default">
                    <div class="panel-heading">
                        Child of  Woman : {{$womanName}}
                    </div>
                    <!-- /.panel-heading -->
               
            </p>
            <table class="table table-striped table-bordered detail-view">
                    <tbody>                                  
                            <tr>
                                <th>Gender</th>
                                <td>{{$data->gender}}</td>
                            </tr>                                     
                            <tr>
                                <th>Weight</th>
                                <td>{{$data->weight}}</td>
                            </tr>                                     
                            <tr>
                                <th>Premature Birth</th>
                                <td>{{$data->premature_birth}}</td>
                            </tr>                                     
                            <tr>
                                <th>Baby Alive</th>
                                <td>{{$data->baby_alive}}</td>
                            </tr>                                     
                            <tr>
                                <th>Baby Status</th>
                                <td>
                                  @php($complex = explode(',',$data->baby_status))
                                  @if($complex[0]=="true")
                                      स्वस्थ,
                                  @endif
                                  @if($complex[1]=="true")
                                      तुरुन्त रोएको,
                                  @endif
                                  @if($complex[2]=="true")
                                      श्वास फेर्न गाह्रो भएको,
                                  @endif

                                  @if($complex[3]=="true")
                                      विकलाङ्ग
                                  @endif
                                </td>
                            </tr>                                     
                            <tr>
                                <th>Advice</th>
                                <td>{{$data->advice}}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{$data->created_at->diffForHumans()}}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{$data->updated_at->diffForHumans()}}</td>
                            </tr>                             
                    </tbody>
            </table>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->