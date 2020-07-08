                <div class="panel panel-default">
                    <div class="panel-heading">
                        Delivery : {{\App\Models\Woman::getWomanName($data->woman_token)}}
                    </div>
                    <!-- /.panel-heading -->
               
            </p>
            <table class="table table-striped table-bordered detail-view">
                            <tbody>
                                     
                                        <tr>
                                            <th width="30%">Delivery Date</th>
                                            <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($data->delivery_date)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Delivery Time</th>
                                            <td>{{$data->delivery_time}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Delivery Place</th>
                                            <td>{{$data->delivery_place}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Presentation</th>
                                            <td>{{$data->presentation}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Delivery Type</th>
                                            <td>{{$data->delivery_type}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Complexity</th>
                                            <td>
                                                @php($complex = explode(',',$data->complexity))
                                                  @if($complex[0]=="true")
                                                      अत्यधिक रक्तश्राव,
                                                  @endif
                                                  @if($complex[0]=="true")
                                                      ≥ १२ घण्टा बेथा लागेको,
                                                  @endif
                                                  @if($complex[1]=="true")
                                                      साल नझरेको
                                                  @endif
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Other Problem</th>
                                            <td>{{$data->other_problem}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Advice</th>
                                            <td>{{$data->advice}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Miscarriage Status</th>
                                            <td>
                                                @if($data->miscarriage_status=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>                                    
                                        <tr>
                                            <th>Delivery By</th>
                                            <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($data->delivery_by)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($data->status=='0')
                                                    Previouse Delivery
                                                @else
                                                    Current Delivery
                                                @endif
                                            </td>
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