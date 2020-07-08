                <div class="panel panel-default">
                    <div class="panel-heading">
                        Lab Test : {{\App\Models\Woman::getWomanName($data->woman_token)}}
                    </div>
                    <!-- /.panel-heading -->
               
            </p>
            <table class="table table-striped table-bordered detail-view">
                               <tbody>                                 
                                        <tr>
                                            <th>Test Date</th>
                                            <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($data->test_date)}}</td>
                                        </tr>                                    
                                        <tr>
                                            <th>Hb</th>
                                            <td>{{$data->hb}}</td>
                                        </tr>                                    
                                        <tr>
                                            <th>Albumin</th>
                                            <td>{{$data->albumin}}</td>
                                        </tr>                                   
                                        <tr>
                                            <th>Urine Protin</th>
                                            <td>{{$data->urine_protein}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Urine Sugar</th>
                                            <td>{{$data->urine_sugar}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Blood Sugar</th>
                                            <td>{{$data->blood_sugar}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Hbsag</th>
                                            <td>{{$data->hbsag}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Vdrl</th>
                                            <td>{{$data->vdrl}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Retro Virus</th>
                                            <td>{{$data->retro_virus}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Other</th>
                                            <td>{{$data->other}}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($data->status=='0')
                                                    Previouse Pregnancy
                                                @else
                                                    Current Pregnancy
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