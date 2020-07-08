                <div class="panel panel-default">
                    <div class="panel-heading">
                        Anc : {{\App\Models\Woman::getWomanName($data->woman_token)}}
                    </div>
                    <!-- /.panel-heading -->
               
            </p>
            <table class="table table-striped table-bordered detail-view">
                            <tbody>                                    
                                        <tr>
                                            <th>Woman</th>
                                            <td>{{\App\Models\Woman::getWomanName($data->woman_token)}}</td>
                                        </tr>                                      
                                        <tr>
                                            <th>Visit Date</th>
                                            <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($data->visit_date)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Weight</th>
                                            <td>{{$data->weight}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Anemia</th>
                                            <td>{{$data->anemia}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Swelling</th>
                                            <td>{{$data->swelling}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Blood Pressure</th>
                                            <td>{{$data->blood_pressure}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Uterus Height</th>
                                            <td>{{$data->uterus_height}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Presentation</th>
                                            <td>{{$data->baby_presentation}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Heart Beat</th>
                                            <td>{{$data->baby_heart_beat}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Other</th>
                                            <td>{{$data->other}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Iron Pills</th>
                                            <td>{{$data->iron_pills}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Worm Medicine</th>
                                            <td>{{$data->worm_medicine}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Td Vaccine</th>
                                            <td>{{$data->td_vaccine}}</td>
                                        </tr>                                      
                                        <tr>
                                            <th>Next Visit Date</th>
                                            <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($data->next_visit_date)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Checked By</th>
                                            <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($data->checked_by)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Healthpost</th>
                                            <td>{{\App\Models\Healthpost::getHealthpost($data->hp_code)}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Record Status</th>
                                            <td>
                                                @if($data->status=='0')
                                                    Previouse Pregnancy
                                                @else
                                                    Current Pregnanay
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