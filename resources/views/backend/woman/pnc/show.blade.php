                <div class="panel panel-default">
                    <div class="panel-heading">
                        Pnc : {{\App\Models\Woman::getWomanName($data->woman_token)}}
                    </div>
                    <!-- /.panel-heading -->
               
            </p>
            <table class="table table-striped table-bordered detail-view">
                                <tbody>
                                     
                                        <tr>
                                            <th width="30%">Visit Date</th>
                                            <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($data->date_of_visit)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Visit Time</th>
                                            <td>{{$data->visit_time}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Mother Status</th>
                                            <td>{{$data->mother_status}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Status</th>
                                            <td>{{$data->baby_status}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Advice</th>
                                            <td>{{$data->advice}}</td>
                                        </tr>                                       
                                        <tr>
                                            <th>Family Plan</th>
                                            <td>{{$data->family_plan}}</td>
                                        </tr>                                    
                                        <tr>
                                            <th>Checked By</th>
                                            <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($data->checked_by)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($data->status=='0')
                                                    No
                                                @else
                                                    Yes
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