<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Yagiten\Nepalicalendar\Calendar;

use App\Models\SampleCollection;


class ScriptController extends Controller
{
    public function collectionDateFix(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $sample_collections = SampleCollection::whereNull('collection_date_en')->whereNotNull('collection_date_np')->get();
        // dd($sample_collections);
        foreach($sample_collections as $key => $sample) {
            if($sample->collection_date_np) {
                $collection_date_np_array = explode("-", $sample->collection_date_np);
                $collection_date_en = Calendar::nep_to_eng($collection_date_np_array[0], $collection_date_np_array[1], $collection_date_np_array[2])->getYearMonthDayNepToEng();

                $sample->update(['collection_date_en' => $collection_date_en]);
            }
        }

        $sample_collections_second = SampleCollection::whereNull('collection_date_np')->whereNotNull('collection_date_en')->get();
        foreach($sample_collections_second as $key => $sample) {
            if($sample->collection_date_en) {
                $collection_date_en_array = explode("-", $sample->collection_date_en);
                $collection_date_np = Calendar::eng_to_nep($collection_date_en_array[0], $collection_date_en_array[1], $collection_date_en_array[2])->getYearMonthDayEngToNep();

                $sample->update(['collection_date_np' => $collection_date_np]);
            }
        }

        $sample_collections_third = SampleCollection::whereNull('collection_date_en')->whereNull('collection_date_np')->get();
        foreach($sample_collections_second as $key => $sample) {
            $collection_date_en_array = explode("-", Carbon::parse($sample->created_at)->format('Y-m-d'));
            $collection_date_np = Calendar::eng_to_nep($collection_date_en_array[0], $collection_date_en_array[1], $collection_date_en_array[2])->getYearMonthDayEngToNep();

            $sample->update([
                'collection_date_np' => $collection_date_np,
                'collection_date_en' => Carbon::parse($sample->created_at)->format('Y-m-d')
            ]);
        }

        echo 'Completed';
    }

    public function reportingDateFix(){
        $sample_collections = SampleCollection::where(function ($query) {
                $query->where('result','4')
                ->orWhere('result', '3');
            })
        ->whereNull('reporting_date_np')->whereNotNull('reporting_date_en')->get();
        foreach($sample_collections as $key => $sample) {
            if($sample->reporting_date_en) {
                $reporting_date_en_array = explode("-", Carbon::parse($sample->reporting_date_en)->format('Y-m-d'));
                $reporting_date_np = Calendar::eng_to_nep($reporting_date_en_array[0], $reporting_date_en_array[1], $reporting_date_en_array[2])->getYearMonthDayEngToNep();

                $sample->update(['reporting_date_np' => $reporting_date_np]);
            }
        }

        $sample_collections_second = SampleCollection::whereIn('result', ['3', '4'])->whereNull('reporting_date_en')->whereNotNull('reporting_date_np')->get();
        foreach($sample_collections_second as $key => $sample) {
            if($sample->reporting_date_np) {
                $reporting_date_np_array = explode("-", $sample->collection_date_n);
                $reporting_date_en = Calendar::nep_to_eng($reporting_date_np_array[0], $reporting_date_np_array[1], $reporting_date_np_array[2])->getYearMonthDayNepToEng();

                $sample->update(['reporting_date_en' => $reporting_date_en]);
            }
        }

        echo 'Completed';
    }

    public function receivedDateFix(){
        $sample_collections = SampleCollection::whereNull('received_date_np')->whereNotNull('received_date_en')->get();
        foreach($sample_collections as $key => $sample) {
            if($sample->received_date_en) {
                $received_date_en_array = explode("-", Carbon::parse($sample->received_date_en)->format('Y-m-d'));
                $received_date_np = Calendar::eng_to_nep($received_date_en_array[0], $received_date_en_array[1], $received_date_en_array[2])->getYearMonthDayEngToNep();

                $sample->update(['received_date_np' => $received_date_np]);
            }
        }

        $sample_collections_second = SampleCollection::whereNull('received_date_en')->whereNotNull('received_date_np')->get();
        foreach($sample_collections_second as $key => $sample) {
            if($sample->received_date_np) {
                $received_date_np_array = explode("-", $sample->collection_date_n);
                $received_date_en = Calendar::nep_to_eng($received_date_np_array[0], $received_date_np_array[1], $received_date_np_array[2])->getYearMonthDayNepToEng();

                $sample->update(['received_date_en' => $received_date_en]);
            }
        }

        echo 'Completed';
    }
}
