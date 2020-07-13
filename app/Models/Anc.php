<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Yagiten\Nepalicalendar\Calendar;

class Anc extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'ancs';

    protected $fillable = ['token', 'woman_token', 'service_for', 'visit_date_np', 'visit_date', 'weight', 'anemia', 'swelling', 'blood_pressure', 'uterus_height', 'baby_presentation', 'baby_heart_beat', 'other', 'iron_pills', 'worm_medicine', 'td_vaccine', 'checked_by', 'hp_code', 'status', 

    'current_province', 'current_district', 'current_municipality', 'current_ward', 'current_tole', 'rdt_test', 'rdt_result', 'rdt_test_date', 'pcr_test', 'pcr_result', 'pcr_test_date', 'problems_and_suggestions', 'checked_by_name',

    'created_at', 'updated_at', 'situation']; // 24-28 weeks from lmp_date_en

    protected $dates = ['deleted_at'];

    public function woman()
    {
        return $this->belongsTo('App\Models\Woman', 'woman_token', 'token');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('visit_date', [$from, $to]);
    }

    public function dateInNp($date)
    {
        if (!empty($date)) {
            $year = Carbon::parse($date)->format('Y');
            $month = Carbon::parse($date)->format('m');
            $day = Carbon::parse($date)->format('d');
            $in_nepali = Calendar::eng_to_nep($year, $month, $day)->getYearMonthDayEngToNep();

            return $in_nepali;
        }
    }

    public function babyPresentationOptions()
    {
        return ['None', 'Cephalic', 'Breech', 'Shoulder'];
    }

    public function sewllingOptions()
    {
        return ['None','Legs','Face','Hand','Both','Face and Legs','Hand and Legs','All','Hand and Face' ];
    }

    public function ancVisitForReport($womanToken, $request)
    {
        $hp_code = $request->hp_code;
        $municipality_id = $request->municipality_id;
        $ward_no = $request->ward_no;
        $district_id = $request->district_id;
        $province_id = $request->province_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $ancs = \DB::table('ancs')
            ->select('women.token as woman_token', 'women.lmp_date_en', 'ancs.visit_date as anc_visit_date')
            ->leftjoin('women', 'ancs.woman_token', '=', 'women.token')
            ->where([['ancs.woman_token', $womanToken], ['ancs.status', '1']]);

        if ($hp_code != "") {
            $ancs->where('women.hp_code', $hp_code);
        } elseif ($municipality_id != "" && $ward_no != "") {
            $ancs->join('healthposts', 'healthposts.hp_code', '=', 'women.hp_code')
                ->where([['healthposts.municipality_id', $municipality_id], ['healthposts.ward_no', $ward_no]]);
        } elseif ($municipality_id != "") {
            $ancs->join('healthposts', 'healthposts.hp_code', '=', 'women.hp_code')
                ->where('healthposts.municipality_id', $municipality_id);
        } elseif ($district_id != "") {
            $ancs->join('healthposts', 'healthposts.hp_code', '=', 'women.hp_code')
                ->where('healthposts.district_id', $district_id);
        } elseif ($province_id != "") {
            $ancs->join('healthposts', 'healthposts.hp_code', '=', 'women.hp_code')
                ->where('healthposts.province_id', $province_id);
        }

        $ancs = $ancs->get();


        $fourthMonthAnc = '';
        $seventhMonth = '';
        $eightMonthAnc = '';
        $nineMonthAnc = '';

        foreach ($ancs as $anc) {
            $lmpDateEn = $anc->lmp_date_en;
            $fourthMonthAncDate = date('Y-m-d', strtotime($lmpDateEn . ' + 120 days'));
            $seventhMonthAncDate = date('Y-m-d', strtotime($lmpDateEn . ' + 180 days'));
            $eightMonthAncDate = date('Y-m-d', strtotime($lmpDateEn . ' + 240 days'));
            $nineMonthAncDate = date('Y-m-d', strtotime($lmpDateEn . ' + 280 days'));
            if ($anc->anc_visit_date !== null && $anc->anc_visit_date <= $fourthMonthAncDate) {
                $fourthMonthAnc = '1';
            }
            if ($fourthMonthAncDate <= $anc->anc_visit_date && $anc->anc_visit_date <= $seventhMonthAncDate) {
                $seventhMonth = '1';
            }
            if ($seventhMonthAncDate <= $anc->anc_visit_date && $anc->anc_visit_date <= $eightMonthAncDate) {
                $eightMonthAnc = '1';
            }
            if ($eightMonthAncDate <= $anc->anc_visit_date && $anc->anc_visit_date <= $nineMonthAncDate) {
                $nineMonthAnc = '1';
            }
        }

        return [
            'fourthMonthAnc' => $fourthMonthAnc,
            'seventhMonth' => $seventhMonth,
            'eightMonthAnc' => $eightMonthAnc,
            'nineMonthAnc' => $nineMonthAnc
        ];


    }

}
