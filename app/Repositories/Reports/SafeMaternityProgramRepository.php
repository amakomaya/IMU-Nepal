<?php


namespace App\Repositories\Reports;

use App\Helpers\GetHealthpostCodes;
use App\Models\Anc;
use App\Models\Delivery;
use App\Models\Woman;
use App\Reports\DateFromToRequest;
use Carbon\Carbon;
use Yagiten\Nepalicalendar\Calendar;

class SafeMaternityProgramRepository
{

    public function ancCollection($request)
    {
        $hpCodes = GetHealthpostCodes::filter($request);

        $date = $this->dataFromAndTo($request);

        if (array_key_exists("ward_no", $hpCodes)) {
            dd("HELLO FROM WARD");
        } else {
            $anc_visited_woman_token = Anc::whereIn('hp_code', $hpCodes)
                ->fromToDate($date['from_date'], $date['to_date'])
                ->active()
                ->pluck('woman_token')->unique();
        }

        $data = Woman::whereIn('token', $anc_visited_woman_token)->active()->get();

        $collection = $data->filter(function ($value) use ($date) {
            $value['first_visited_completed'] = 0;
            $value['second_visited_completed'] = 0;
            $value['third_visited_completed'] = 0;
            $value['forth_visited_completed'] = 0;
            $value['forth_month_visited'] = 0;
            $value['first_time'] = 0;

            $value['visited_dates'] = !empty($value->ancs) ? $value->ancs()->whereDate('visit_date', '<', $date['to_date'])->pluck('visit_date') : [];

            if ($value['visited_dates']->count() >= 1) {
                if ($value->isFirstAnc($value, $date)) {
                    $value['first_visited_completed'] = 1;
                }
                if ($value->isSecondAnc($value, $date)) {
                    $value['second_visited_completed'] = 1;
                }
                if ($value->isThirdAnc($value, $date)) {
                    $value['third_visited_completed'] = 1;
                }
                if ($value->isForthAnc($value, $date)) {
                    $value['forth_visited_completed'] = 1;
                }
                if ($value->isForthMonthAnc($value, $date)) {
                    $value['forth_month_visited'] = 1;
                }
                if ($value->isFirstTimeAnc($value, $date)) {
                    $value['first_time'] = 1;
                }
            }
            return $value;
        });
        return $collection;        
    }

    private function dataFromAndTo($request)
    {
        return DateFromToRequest::dateFromTo($request);
    }

    public function deliveryCollection($request)
    {
        $hpCodes = GetHealthpostCodes::filter($request);

        $date = $this->dataFromAndTo($request);

        if (array_key_exists("ward_no", $hpCodes)) {
            dd("HELLO FROM WARD");
        } else {
            $delivery_woman_token = Delivery::distinct()->select('woman_token')
                        ->whereIn('hp_code', $hpCodes)
                        ->fromToDate($date['from_date'], $date['to_date'])
                        ->active()
                        ->groupBy('woman_token')
                        ->get('woman_token')
                        ->toArray();
        }
        $data = Woman::whereIn('token', $delivery_woman_token)->active()->get();

        return $data;
    }
}