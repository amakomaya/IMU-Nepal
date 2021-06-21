<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LabReceivedImport;
use App\Imports\LabResultImport;
use App\Imports\LabReceivedResultImport;
use App\Imports\RegisterSampleCollectionImport;
use App\Imports\RegisterSampleCollectionLabImport;
use App\Imports\BackDate\BackdateLabReceivedImport;
use App\Imports\BackDate\BackdateLabResultImport;
use App\Imports\BackDate\BackdateLabReceivedResultImport;
use App\Imports\BackDate\BackdateRegisterSampleCollectionImport;
use App\Imports\BackDate\BackdateRegisterSampleCollectionLabImport;
use App\Imports\AsymptomaticPoeImport;
use App\Imports\SymptomaticPoeImport;


class BulkUploadController extends Controller
{

    public function list(Request $request){
      return view('backend.bulk-upload.list');
    }

    public function bulkFileHandle(Request $request) {
      $slug = $request->get('slug');
      if ($request->hasFile($slug)) {
        $bulk_file = $request->file($slug);
        try {
          switch($slug) {
            case 'bulk_file_lab_received':
              $import = new LabReceivedImport(auth()->user());
              $successMessage = "Lab Received Data uploaded successfully";
              break;
            case 'bulk_file_lab_result':
              $import = new LabResultImport(auth()->user());
              $successMessage = "Lab Result Data updated successfully";
              break;
            case 'bulk_file_lab_received_result':
              $import = new LabReceivedResultImport(auth()->user());
              $successMessage = "Lab Received & Results Data created successfully";
              break;
            case 'bulk_file_registration_sample_collection':
              $import = new RegisterSampleCollectionImport(auth()->user());
              $successMessage = "Sample Collection Data created successfully.";
              break;
            case 'bulk_file_registration_sample_collection_lab_test':
              $import = new RegisterSampleCollectionLabImport(auth()->user());
              $successMessage = "Sample Collection Data with Lab Test created successfully";
              break;
            case 'bulk_file_asymptomtic_poe':
              $import = new AsymptomaticPoeImport(auth()->user());
              $successMessage = "Asymptomatic POE data registered successfully";
              break;
            case 'bulk_file_symptomtic_poe':
              $import = new SymptomaticPoeImport(auth()->user());
              $successMessage = "Symptomatic POE data registered successfully";
              break;
              
          }
          
          Excel::queueImport($import, $bulk_file);
          $importedRowCount = $import->getImportedRowCount();
          if($importedRowCount == 0) {
            return response()->json([
              'status' => 'fail',
              'message' => [['row' => 0, 'column' => '-', 'error' => ['No data was inserted. Please check if the template is valid or if your excel file has data.'] ]] 
              ], 422
            );
          }
          return response()->json(['message' => 'success',
            'message' => $successMessage,
          ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
          $errors = [];
          $failures = $e->failures();
          $error_msg = '';
          foreach ($failures as $key=>$failure) {
              $errors[$key]['row'] = $failure->row(); // row that went wrong
              $errors[$key]['column'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
              $errors[$key]['error'] = $failure->errors(); // Actual error messages from Laravel validator
              $errors[$key]['values'] = $failure->values(); // The values of the row that has failed.
          }
          return response()->json([
            'status' => 'fail',
            'message' => $errors
            ], 422
          );
        }
      } else {
        return response()->json(['status' => 'fail',
          'message' => "Couldnt find the file."
      ]);
      }
    }
}
