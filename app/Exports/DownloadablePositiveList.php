<?php

namespace App\Exports;

use App\Helpers\GetHealthpostCodes;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DownloadablePositiveList implements FromCollection, WithHeadings
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        {
            $response = FilterRequest::filter($this->request);
            $hpCodes = GetHealthpostCodes::filter($response);

            $check_at_1330 = Carbon::parse('today 1:30pm');

            if($check_at_1330 < Carbon::now()){
                // 1 pm today + current
                $date_from = Carbon::parse('today 1:30pm');
                $date_to = Carbon::now();
            }else{
                // 1pm yesterday + current
                $date_from = Carbon::parse('yesterday 1:30pm');
                $date_to = Carbon::now();
            }

            $tokens = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)
//                ->whereDate('updated_at', Carbon::today())
                ->whereBetween('updated_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                ->active()
                ->pluck('woman_token');

            $data = SuspectedCase::whereIn('token', $tokens)->with('district', 'municipality', 'ancs')->get();
            return $data->map(function ($item, $key) {
                try {
                    $record = [];
                    $record['sn'] = $key + 1;
                    $record['name'] = $item->name;
                    $record['gender'] = $item->formated_gender;
                    $record['age'] = $item->age;
                    $record['age_unit'] = $item->formated_age_unit;
                    $record['district'] = $item->district->district_name ?? '';
                    $record['municipality'] = $item->municipality->municipality_name ?? '';
                    $record['ward'] = $item->ward;
                    $record['phone'] = $item->emergency_contact_one;
                    $record['phone_two'] = $item->emergency_contact_two;
                    $record['swab_id'] = $item->ancs->last()->token;

                    return $record;
                } catch (\Exception $e) {
                    return [];
                }
            });
        }
    }

    public function headings(): array
    {
        return [
            'S.N.',
            'Full Name',
            'Gender',
            'Age',
            'Age Unit',
            'District',
            'Municipality',
            'Ward',
            'Phone',
            'Phone Two',
            'Swab ID'
        ];
    }
}
