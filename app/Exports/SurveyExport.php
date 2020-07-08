<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class SurveyExport implements FromCollection, WithHeadings
{
	private $data;

	public function __construct($data){
		$this->data = $data;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$surveys = collect(json_decode($this->data, true))->flatten(1);
		$collection = $surveys->map(function ($survey) {
            $survey['index'] = '';
            $survey['fullName'] = $survey['name'];
            $survey['sex'] = $this->getGender($survey['gender'] ?? '');
            $survey['ageAtSurvey'] = $survey['age'] ?? '';
            $survey['mobile'] = $survey['phone'] ?? '';
            $survey['pinPassword'] = $survey['pin'] ?? '';
            $survey['auxiliaryContact'] = $survey['aux_contact'] ?? '';
            $survey['relationOnAuxiliaryContact'] = $this->checkPhoneRelation($survey['relation'] ?? '');
            $survey['province'] = $survey['province_id'] ?? '';
            $survey['district'] = $this->getDistrict($survey['district_id'] ?? '');
            $survey['municipality'] = $this->getMunicipality($survey['municipality_id'] ?? '');
        	$survey['ward_no'] = $survey['ward'] ?? '';
        	$survey['toleDetails'] = $survey['tole'] ?? '';
            $survey['is_travel'] = $this->yesNo($survey['foreigner'] ?? 0);
            $survey['travelDate'] = $survey['travel_date'] ?? '';
			$survey['travelHistory'] = $this->getTravelDetails($date['travel_date'] ?? '', $survey['travel_history']);
            $survey['medicalHistory'] = $this->checkDiseaseHistory($survey['medical_history']);

            $survey['pregnancyStatus'] = $this->yesNo($survey['pregnancy_status'] ?? 0);
            $survey['wantImmediateRescue'] = $this->yesNo($survey['immediate_rescue'] ?? 0);
            $survey['needHelp'] = $this->yesNo($survey['need_help'] ?? 0);
            $survey['isInfected'] = $this->yesNo($survey['infected_status'] ?? 0);
            $survey['allSymptoms'] = $this->checkSymptoms($survey['symptoms']);
            $survey['locLongitude'] = $survey['longitude'] ?? '';
        	$survey['locLatitude'] = $survey['latitude'] ?? '';
        	$survey['createdAt'] = $survey['created_at'] ?? '';

			return collect($survey)->only(['index','fullName', 'sex', 'ageAtSurvey', 'mobile', 'pinPassword', 'auxiliaryContact', 'relationOnAuxiliaryContact', 'province', 'district', 'municipality', 'ward_no', 'toleDetails', 'is_travel', 'travelDate', 'travelHistory', 'medicalHistory', 'pregnancyStatus', 'wantImmediateRescue', 'needHelp', 'isInfected', 'allSymptoms', 'locLongitude', 'locLatitude', 'createdAt']);
        })
        ->sortByDesc('createdAt')->groupBy(function ($item){
            return Carbon::parse($item['createdAt'])->format('Y-m-d'); 
        }); 

        return $collection;
    }

    public function headings() : array
    {
    	return [
    		'S.N.',
    		'Full Name', 
    		'Gender', 
    		'Age', 
    		'Contact', 
    		'Pin Password', 
    		'Auxiliary Contact', 
    		'Relation On Auxiliary Contact', 
    		'Province', 
    		'District', 
    		'Municipality', 
    		'Ward No', 
    		'Tole', 
    		'Is Travel', 
    		'Travel Date', 
    		'Travel History', 
    		'Medical History', 
    		'Pregnancy Status', 
    		'Want Immediate Rescue', 
    		'Need Help',
    		'Is Infected',
    		'All Symptoms',
    		'Longitude', 
    		'Latitude', 
    		'created At'
    	];    
    }

    public function checkDiseaseHistory($data){
    	$arr = [];
	    if (str_contains($data, '1')){
	    	array_push($arr, 'दम');
	    }
	    if (str_contains($data, '2')){
	    	array_push($arr, 'मधुमेह (सुगर)');
	    }
	    if (str_contains($data, '3')){
	    	array_push($arr, 'उच्च / निम्न रक्तचाप');
	    }
	    if (str_contains($data, '4')){
	    	array_push($arr, 'टि. बि.');
	    }
	    if (str_contains($data, '5')){
	    	array_push($arr, 'मुटुको रोग');
	    }
	    if (str_contains($data, '6')){
	    	array_push($arr, 'क्यान्सर');
	    }
	    if (str_contains($data, '7')){
	    	array_push($arr, 'एचआईँभि');
	    }
	    if (str_contains($data, '7')){
	    	array_push($arr, 'अन्य');
	    }
	    return implode(',',$arr);
	}

	public function checkSymptoms($data){
		$arr = [];
	    if (str_contains($data, '1')){
	    	array_push($arr, 'रुघा सहित नाकबाट पानि बगिरहेको');
	    }
	    if (str_contains($data, '2')){
	    	array_push($arr, 'ज्वरो (१००F+) भन्दा माथि');
	    }
	    if (str_contains($data, '3')){
	    	array_push($arr, 'सुख्खा खोकि');
	    }
	    if (str_contains($data, '4')){
	    	array_push($arr, 'कामनै नगर्दा पनि थकान महशुस भएको');
	    }
	    if (str_contains($data, '5')){
	    	array_push($arr, 'स्वास् फेर्दा गार्हो भएको वा स्याँ स्याँ भएको');
	    }
	    if (str_contains($data, '6')){
	    	array_push($arr, 'घाटि सुख्खा भइ दुखिरहेको');
	    }
	    if (str_contains($data, '7')){
	    	array_push($arr, 'टाउको दुखेको');
	    }
	    if (str_contains($data, '8')){
	    	array_push($arr, 'मामंशपेशि दुखेको');
	    }
	    if (str_contains($data, '9')){
	    	array_push($arr, 'वाकवाकि लागि रहेको');
	    }
	    if (str_contains($data, '10')){
	    	array_push($arr, 'पखला वा पातलो दिशा बारम्बार लाग्ने');
	    }
	   	return implode(',',$arr);
	}

	public function checkPhoneRelation($data){
		$arr = [];
	    if ($data == 1){
	    	array_push($arr, 'Family');
	    }
	    if ($data == 2){
	    	array_push($arr, 'Friend');
	    }
	    if ($data == 3){
	    	array_push($arr, 'Colleague');
	    }
	   	return implode(',',$arr);
	}

	public function getGender($data){
	  if ($data == 1){
	      return "Male";
	    }
	    if ($data == 2){
	      return "Female";
	    }
	    if ($data == 3){
	      return "Other";
	    }
	}

	public function getTravelDetails($date, $history){
		$arr = [];
		if( $date == '' ){
		  return 'No';
		}

		if (str_contains($history, '1')){
			array_push($arr, 'China');
		}; 
		if (str_contains($history, '2')){
			array_push($arr, 'Japan');
		};
		if (str_contains($history, '3')){
			array_push($arr, 'USA');
		};
		if (str_contains($history, '4')){
			array_push($arr, 'Italy');
		};
		if (str_contains($history, '5')){
			array_push($arr, 'Spain');
		};
		if (str_contains($history, '6')){
			array_push($arr, 'S. Korea');
		};
		if (str_contains($history, '7')){
			array_push($arr, 'India');
		};
		if (str_contains($history, '8')){
			array_push($arr, 'Iran');
		};
		if (str_contains($history, '9')){
			array_push($arr, 'U.K.');
		};
		if (str_contains($history, '10')){
			array_push($arr, 'Other');
		};
		return implode(',',$arr);
	}

	public function yesNo($data){
	if( $data == 1 ){
	  return 'Yes';
	}
	  return 'No';
	}

	public function getDistrict($id){
		return \App\Models\District::where('id', $id)->get()->first()->district_name ?? '';
	}

	public function getMunicipality($id){
		return \App\Models\Municipality::where('id', $id)->get()->first()->municipality_name ?? '';
	}
}