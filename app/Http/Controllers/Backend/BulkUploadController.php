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


class BulkUploadController extends Controller
{

    public function list(Request $request){
      return view('backend.bulk-upload.list');
    }

    public function labReceived (Request $request) {
      if ($request->hasFile('bulk_file_lab_received')) {
        $bulk_file = $request->file('bulk_file_lab_received');
        try {
          $import = new LabReceivedImport(auth()->user());
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
            'message' => 'Lab Received Data uploaded successfully',
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
        return response()->json(['status' => 'success',
            'message' => "Uploading"
          ]);
      } else {
        return response()->json(['status' => 'fail',
          'message' => "Couldnt find the file."
      ]);
      }
    }

    public function labResult (Request $request) {
      if ($request->hasFile('bulk_file_lab_result')) {
        $bulk_file = $request->file('bulk_file_lab_result');
        try {
          $import = new LabResultImport(auth()->user());
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
            'message' => 'Lab Results Data updated successfully',
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
        return response()->json(['status' => 'success',
            'message' => "Uploading"
          ]);
      }
    }

    public function labReceivedResult (Request $request) {
      if ($request->hasFile('bulk_file_lab_received_result')) {
        $bulk_file = $request->file('bulk_file_lab_received_result');
        try {
          $import = new LabReceivedResultImport(auth()->user());
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
            'message' => 'Lab Received & Results Data created successfully',
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
        return response()->json(['status' => 'success',
            'message' => "Uploading"
          ]);
      }
    }

    public function registrationSampleCollection (Request $request) {
      $bed_status = json_decode(request()->bed_status);
      if ($request->hasFile('bulk_file_registration_sample_collection')) {
        $bulk_file = $request->file('bulk_file_registration_sample_collection');
        try {
          $import = new RegisterSampleCollectionImport(auth()->user());
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
            'message' => 'Sample Collection Data created successfully',
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
        return response()->json(['status' => 'success',
            'message' => "Uploading"
          ]);
      }
    }

    public function registrationSampleCollectionLabTest (Request $request) {
      if ($request->hasFile('bulk_file_registration_sample_collection_lab_test')) {
        $bulk_file = $request->file('bulk_file_registration_sample_collection_lab_test');
        try {
          $import = new RegisterSampleCollectionLabImport(auth()->user());
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
            'message' => 'Sample Collection Data created successfully',
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
        return response()->json(['status' => 'success',
            'message' => "Uploading"
          ]);
      }
    }
}
