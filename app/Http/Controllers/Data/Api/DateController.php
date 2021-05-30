<?php



namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\ContactTracing;
use App\Models\Organization;
use App\Models\PaymentCase;
use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\SuspectedCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;
use App\User;
use Illuminate\Validation\Rule;

class DateController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'date' => Carbon::now()->format(('Y-m-d'))
        ]);
    }
}