<?php 

namespace App\Repositories\Reports;

use App\Models\Woman;
use App\Models\BabyDetail;
use App\Models\Anc;
use App\Helpers\GetHealthpostCodes;
use App\Reports\DateFromToRequest;
use Carbon\Carbon;
use Yagiten\Nepalicalendar\Calendar;

class DashboardRepository
{
    protected $model;

    public function __construct(Woman $womanModel, BabyDetail $babyModel, Anc $ancModel){
        $this->womanModel = $womanModel;
        $this->babyModel = $babyModel;
        $this->ancModel = $ancModel;
    }

    public function all($request)
    {
        $hpCodes = GetHealthpostCodes::filter($request);
        $date = $this->dataFromAndTo($request);

        if (array_key_exists("ward_no",$hpCodes)) {
            $registered_woman = 0;
//            $hp_code_by_municipality = \App\Models\Healthpost::where('municipality_id', $hpCodes['municipality_id'])->get('hp_code')->toArray();
            $registered_baby = 0;
            $anc_completed_at_least_one_visit = 0;
        }else{
            $registered_woman = $this->womanModel::whereIn('hp_code', $hpCodes)->fromToDate($date['from_date'], $date['to_date'])->active()->get()->count();
            $registered_baby = $this->babyModel->whereIn('hp_code', $hpCodes)->fromToDate($date['from_date'], $date['to_date'])->active()->isAlive()->get()->count();
            $anc_completed_at_least_one_visit = Anc::distinct()->select('woman_token')->whereIn('hp_code', $hpCodes)->fromToDate($date['from_date'], $date['to_date'])->active()->groupBy('woman_token')->get()->count();

        }


        return [
            'registered_woman' => $registered_woman,
            'registered_baby' => $registered_baby,
            'anc_completed_at_least_one_visit' => $anc_completed_at_least_one_visit
        ];
    }

    private function dataFromAndTo($request)
    {
        return DateFromToRequest::dateFromTo($request);
    }
}