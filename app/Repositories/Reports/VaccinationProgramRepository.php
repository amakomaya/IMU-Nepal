<?php

namespace App\Repositories\Reports;

use App\Helpers\GetHealthpostCodes;
use App\Models\VaccinationRecord;
use App\Reports\DateFromToRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yagiten\Nepalicalendar\Calendar;

class VaccinationProgramRepository
{
    public function all($request)
    {
        $hpCodes = GetHealthpostCodes::filter($request);
        $immunizedChild = $this->immunizedChild($hpCodes, $request);
        $vailStock = $this->receivedExpenseDose($hpCodes, $request);
        $aefiCases = $this->aefiCases($hpCodes, $request);

        $data[] = compact('immunizedChild', 'vailStock', 'aefiCases');

        return $data;

    }

    private function immunizedChild($response, $request)
    {
//        dd($request);
        $bcgFirst = 0;
        $pvFirst = 0;
        $pvSecond = 0;
        $pvThird = 0;
        $opvFirst = 0;
        $opvSecond = 0;
        $opvThird = 0;
        $pcvFirst = 0;
        $pcvSecond = 0;
        $pcvThird = 0;
        $fipvFirst = 0;
        $fipvSecond = 0;
        $mrFirst = 0;
        $mrSecond = 0;
        $jeFirst = 0;
        $rvFirst = 0;
        $rvSecond = 0;
        $pvThirdAndOpvThirdAfeterOneYear = 0;
        $tdFirst = 0;
        $tdSecond = 0;
        $tdThird = 0;
        $tdImmunizedRecordFirst = 0;
        $tdImmunizedRecordSecond = 0;
        $tdImmunizedRecordPlus = 0;
    
        if (array_key_exists("ward_no", $response)) {
            dd("HELLO FROM WARD");
        } else {
//            $immunizedRecord = DB::table('vaccination_records')
            $immunizedRecord = VaccinationRecord::hasVialImage()
                ->whereIn('hp_code', $response);
            $tdImmunizedRecord = \App\Models\Woman\Vaccination::where('vaccine_type', 0)->whereIn('hp_code', $response);
        }

        $date = $this->dataFromAndTo($request);

        $immunizedRecord = $immunizedRecord->vaccinatedFromToDate($date['from_date'], $date['to_date'])->active()->get();
        $tdImmunizedRecord = $tdImmunizedRecord->whereBetween('vaccinated_date_en', [$date['from_date'], $date['to_date']])->active();

        $tdImmunizedRecordGet = $tdImmunizedRecord->get();

        $tdRecord = $tdImmunizedRecord->select('woman_token', DB::raw('count(*) as total'))
        ->groupBy(['woman_token']);

        foreach($tdRecord->get() as $value){
            if ($value['total'] == 1) {
                $tdImmunizedRecordFirst++;
            }
            if ($value['total'] == 2) {
                $tdImmunizedRecordSecond++;
            }
            if ($value['total'] > 2) {
                $tdImmunizedRecordPlus++;
            }
        }

        foreach ($immunizedRecord as $data) {
            if ($data->vaccine_name == 'BCG') {
                $bcgFirst++;
            }
            if ($data->vaccine_name == 'Pentavalent' && $data->vaccine_period == '6W') {
                $pvFirst++;
            }
            if ($data->vaccine_name == 'Pentavalent' && $data->vaccine_period == '10W') {
                $pvSecond++;
            }
            if ($data->vaccine_name == 'Pentavalent' && $data->vaccine_period == '14W') {
                $pvThird++;
            }
            if ($data->vaccine_name == 'OPV' && $data->vaccine_period == '6W') {
                $opvFirst++;
            }
            if ($data->vaccine_name == 'OPV' && $data->vaccine_period == '10W') {
                $opvSecond++;
            }
            if ($data->vaccine_name == 'OPV' && $data->vaccine_period == '14W') {
                $opvThird++;
            }
            if ($data->vaccine_name == 'PCV' && $data->vaccine_period == '6W') {
                $pcvFirst++;
            }
            if ($data->vaccine_name == 'PCV' && $data->vaccine_period == '10W') {
                $pcvSecond++;
            }
            if ($data->vaccine_name == 'PCV' && $data->vaccine_period == '9M') {
                $pcvThird++;
            }
            if ($data->vaccine_name == 'FIPV' && $data->vaccine_period == '6W') {
                $fipvFirst++;
            }
            if ($data->vaccine_name == 'FIPV' && $data->vaccine_period == '14W') {
                $fipvSecond++;
            }
            if ($data->vaccine_name == 'RV' && $data->vaccine_period == '6W') {
                $rvFirst++;
            }
            if ($data->vaccine_name == 'RV' && $data->vaccine_period == '10W') {
                $rvSecond++;
            }
            if ($data->vaccine_name == 'MR' && $data->vaccine_period == '9M') {
                $mrFirst++;
            }
            if ($data->vaccine_name == 'MR' && $data->vaccine_period == '15M') {
                $mrSecond++;
            }
            if ($data->vaccine_name == 'JE') {
                $jeFirst++;
            }
        }


        return [
            'bcgFirst' => $bcgFirst,
            'pvFirst' => $pvFirst,
            'pvSecond' => $pvSecond,
            'pvThird' => $pvThird,
            'opvFirst' => $opvFirst,
            'opvSecond' => $opvSecond,
            'opvThird' => $opvThird,
            'pcvFirst' => $pcvFirst,
            'pcvSecond' => $pcvSecond,
            'pcvThird' => $pcvThird,
            'fipvFirst' => $fipvFirst,
            'fipvSecond' => $fipvSecond,
            'mrFirst' => $mrFirst,
            'mrSecond' => $mrSecond,
            'jeFirst' => $jeFirst,
            'rvFirst' => $rvFirst,
            'rvSecond' => $rvSecond,
            'pvThirdAndOpvThirdAfeterOneYear' => $pvThirdAndOpvThirdAfeterOneYear,
            'tdFirst' => $tdFirst,
            'tdSecond' => $tdSecond,
            'tdThird' => $tdThird,
            'tdImmunizedRecord' => $tdImmunizedRecordGet,
            'tdImmunizedRecordFirst' => $tdImmunizedRecordFirst,
            'tdImmunizedRecordSecond' => $tdImmunizedRecordSecond,
            'tdImmunizedRecordPlus' => $tdImmunizedRecordPlus,
        ];
    }

    private function receivedExpenseDose(array $hpCodes, $request)
    {
        $receivedExpenseDose = DB::table('vaccine_vial_stocks')->whereIn('hp_code', $hpCodes);
        $date = $this->dataFromAndTo($request);

        $receivedExpenseDose = $receivedExpenseDose->whereBetween('created_at', [$date['from_date'], $date['to_date']])->get();

        $bcgReceived = $receivedExpenseDose->where('name', 'BCG')->sum('new_dose');
        $pentavalentReceived = $receivedExpenseDose->where('name', 'Pentavalent')->sum('new_dose');
        $opvReceived = $receivedExpenseDose->where('name', 'OPV')->sum('new_dose');
        $pcvReceived = $receivedExpenseDose->where('name', 'PCV')->sum('new_dose');
        $mrReceived = $receivedExpenseDose->where('name', 'MR')->sum('new_dose');
        $jeReceived = $receivedExpenseDose->where('name', 'JE')->sum('new_dose');
        $fipvReceived = $receivedExpenseDose->where('name', 'FIPV')->sum('new_dose');
        $rotaReceived = $receivedExpenseDose->where('name', 'RV')->sum('new_dose');
        $tdReceived = $receivedExpenseDose->where('name', 'TD')->sum('new_dose');

        $vaccine_expense = DB::table('vial_details')
            ->whereIn('hp_code', $hpCodes)
            ->whereNotIn('vial_opened_date', ['0000-00-00 00:00:00']);

        $vaccine_expense = $vaccine_expense->whereBetween('created_at', [$date['from_date'], $date['to_date']])->groupBy('vial_image')->get();

        $bcgExpense = $vaccine_expense->where('vaccine_name', 'BCG')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $pentavalentExpense = $vaccine_expense->where('vaccine_name', 'Pentavalent')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $opvExpense = $vaccine_expense->where('vaccine_name', 'OPV')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $pcvExpense = $vaccine_expense->where('vaccine_name', 'PCV')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $mrExpense = $vaccine_expense->where('vaccine_name', 'MR')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $jeExpense = $vaccine_expense->where('vaccine_name', 'JE')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $fipvExpense = $vaccine_expense->where('vaccine_name', 'FIPV')->where('vial_used_dose','!=','0')->sum('maximum_doses');
        $rotaExpense = $vaccine_expense->where('vaccine_name', 'RV')->where('vial_used_dose','!=','0')->sum('maximum_doses');

        return compact('bcgExpense', 'bcgReceived', 'pentavalentExpense', 'pentavalentReceived', 'opvExpense', 'opvReceived', 'pcvExpense', 'pcvReceived', 'mrExpense', 'mrReceived', 'jeExpense', 'jeReceived', 'fipvExpense', 'fipvReceived', 'rotaExpense', 'rotaReceived', 'tdReceived');
    }

     private function aefiCases(array $hpCodes, $request)
    {
        $aefis = DB::table('aefis')->whereIn('hp_code', $hpCodes);

        $date = $this->dataFromAndTo($request);
        $aefis = $aefis->whereBetween('created_at', [$date['from_date'], $date['to_date']])->get();

        $aefi_bcg = $aefis->where('vaccine', 'like', '%BCG%')->count();

        $aefi_pentavalent = $aefis->where('vaccine', 'like', '%Pentavalent%')->count();
        $aefi_opv = $aefis->where('vaccine', 'like', '%OPV%')->count();
        $aefi_pcv = $aefis->where('vaccine', 'like', '%PCV%')->count();
        $aefi_mr = $aefis->where('vaccine', 'like', '%MR%')->count();
        $aefi_je = $aefis->where('vaccine', 'like', '%JE%')->count();
        $aefi_fipv = $aefis->where('vaccine', 'like', '%FIPV%')->count();
        $aefi_rota = $aefis->where('vaccine', 'like', '%RV%')->count();

        return [
            'aefi_bcg' => $aefi_bcg,
            'aefi_pentavalent' => $aefi_pentavalent,
            'aefi_opv' => $aefi_opv,
            'aefi_pcv' => $aefi_pcv,
            'aefi_mr' => $aefi_mr,
            'aefi_je' => $aefi_je,
            'aefi_fipv' => $aefi_fipv,
            'aefi_rota' => $aefi_rota,
            'aefi_td' => 0
        ];
    }

    private function dataFromAndTo($request)
    {
        return DateFromToRequest::dateFromTo($request);
    }
}