<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use App\Models\DistrictInfo;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Message;
use App\Models\MunicipalityInfo;
use App\Models\province;
use App\Models\provinceInfo;
use App\User;
use Carbon\Carbon;
//use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class MessagesController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        // All threads, ignore deleted/archived participants
//        $threads = Thread::getAllLatest()->get();

        // All threads that user is participating in
         $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();
        return view('messenger.index', compact('threads'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
//        $userId = Auth::id();
//        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $user = auth()->user();
        $labs_token = [];
        if ($user->role == 'main' || $user->role == 'center') {
            $center_tokens = Center::get()->pluck(['token']);
            $labs_token = OrganizationMember::where('role', 'fchv')->get()->pluck(['token']);
            $province_token = provinceInfo::get()->pluck(['token']);
            $district_token = DistrictInfo::get()->pluck(['token']);
            $municipality_token = MunicipalityInfo::get()->pluck(['token']);
            $hospital_or_cict_tokens = Organization::get()->pluck(['token']);
        } if ($user->role == 'province') {
        $provinceInfo = provinceInfo::where('token', $user->token)->first();
        $labs_token = OrganizationMember::where('role', '=', 'fchv')->where('province_id', '=', $provinceInfo->province_id)->get()->pluck(['token']);
        $province_token = provinceInfo::where('province_id', $provinceInfo->province_id)->get()->pluck(['token']);
        $districts_from_province = District::where('province_id', $provinceInfo->province_id)->get()->pluck(['id']);
        $district_token = DistrictInfo::whereIn('district_id', $districts_from_province)->get()->pluck(['token']);
        $municipality_token = MunicipalityInfo::whereIn('district_id', $districts_from_province)->get()->pluck(['token']);
        $hospital_or_cict_tokens = Organization::whereIn('district_id', $districts_from_province)->get()->pluck(['token']);
        $center_tokens = Center::get()->pluck(['token']);

    } if ($user->role == 'dho') {
        $center_tokens = [];
        $dhoInfo = DistrictInfo::where('token', $user->token)->first();
        $labs_token = OrganizationMember::where('role', 'fchv')->where('district_id', $dhoInfo->district_id)->get()->pluck(['token']);
        $province_token = provinceInfo::where('province_id', $dhoInfo->district->province_id)->get()->pluck(['token']);
        $district_token = [];
        $municipality_token = MunicipalityInfo::where('district_id', $dhoInfo->district_id)->get()->pluck(['token']);
        $hospital_or_cict_tokens = Organization::where('district_id', $dhoInfo->district_id)->get()->pluck(['token']);
    }if ($user->role == 'municipality') {
        $municipalityInfo = MunicipalityInfo::where('token', $user->token)->first();
        $labs_token = OrganizationMember::where('role', '=', 'fchv')->where('municipality_id', '=', $municipalityInfo->municipality_id)->get()->pluck(['token']);
        $province_token = provinceInfo::where('province_id', $municipalityInfo->province_id)->get()->pluck(['token']);
        $district_token = DistrictInfo::where('district_id', $municipalityInfo->district_id)->get()->pluck(['token']);
        $hospital_or_cict_tokens = Organization::where('district_id', $municipalityInfo->municipality_id)->get()->pluck(['token']);
        $municipality_token = [];
        $center_tokens = [];
    }
        if ($user->role == 'healthpost') {
            $healthpostInfo = Organization::where('token', $user->token)->first();
            $hospital_or_cict_tokens = Organization::where('municipality_id', $healthpostInfo->municipality_id)->get()->pluck(['token']);
            $labs_token = OrganizationMember::where('role', '=', 'fchv')->get()->pluck(['token']);
            $province_token = [];
            $district_token = DistrictInfo::where('district_id', $healthpostInfo->district_id)->get()->pluck(['token']);
            $municipality_token = MunicipalityInfo::where('municipality_id', $healthpostInfo->municipality_id)->get()->pluck(['token']);
            $center_tokens = [];
        }
        if ($user->role == 'healthworker'){
            $find_user = OrganizationMember::where('token', $user->token)->first();
            $center_tokens = [];
            $district_token = DistrictInfo::where('district_id', $find_user->district_id)->get()->pluck(['token']);
            $municipality_token = MunicipalityInfo::where('municipality_id', $find_user->municipality_id)->get()->pluck(['token']);
            $hospital_or_cict_tokens = Organization::where('municipality_id', $find_user->municipality_id)->get()->pluck(['token']);
            $labs_token = OrganizationMember::where('role', 'fchv')->get()->pluck(['token']);
            $province_token = [];
        }


//        $users = User::where('id', '!=', Auth::id())->get();

        $users = User::all()->sortBy('username');

        $lab_users = $users->whereIn('token', $labs_token);
        $province_users = $users->whereIn('token', $province_token);
        $district_users = $users->whereIn('token', $district_token);
        $municipality_users = $users->whereIn('token', $municipality_token);
        $hospital_or_cict_users = $users->whereIn('token', $hospital_or_cict_tokens);
        $center_users = $users->whereIn('token', $center_tokens);
        $main_users = $users->where('role', 'main');
        $thread->markAsRead($user->id);

        return view('messenger.show', compact('thread', 'users', 'lab_users', 'province_users','district_users', 'municipality_users', 'hospital_or_cict_users', 'center_users', 'main_users'));
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $user = auth()->user();
        $labs_token = [];
        if ($user->role == 'main' || $user->role == 'center') {
            $center_tokens = Center::get()->pluck(['token']);
            $labs_token = OrganizationMember::where('role', 'fchv')->get()->pluck(['token']);
            $province_token = provinceInfo::get()->pluck(['token']);
            $district_token = DistrictInfo::get()->pluck(['token']);
            $municipality_token = MunicipalityInfo::get()->pluck(['token']);
            $hospital_or_cict_tokens = Organization::get()->pluck(['token']);
        } if ($user->role == 'province') {
        $provinceInfo = provinceInfo::where('token', $user->token)->first();
        $labs_token = OrganizationMember::where('role', '=', 'fchv')->where('province_id', '=', $provinceInfo->province_id)->get()->pluck(['token']);
        $province_token = provinceInfo::where('province_id', $provinceInfo->province_id)->get()->pluck(['token']);
        $districts_from_province = District::where('province_id', $provinceInfo->province_id)->get()->pluck(['id']);
        $district_token = DistrictInfo::whereIn('district_id', $districts_from_province)->get()->pluck(['token']);
        $municipality_token = MunicipalityInfo::whereIn('district_id', $districts_from_province)->get()->pluck(['token']);
        $hospital_or_cict_tokens = Organization::whereIn('district_id', $districts_from_province)->get()->pluck(['token']);
        $center_tokens = Center::get()->pluck(['token']);

        } if ($user->role == 'dho') {
            $center_tokens = [];
            $dhoInfo = DistrictInfo::where('token', $user->token)->first();
            $labs_token = OrganizationMember::where('role', 'fchv')->where('district_id', $dhoInfo->district_id)->get()->pluck(['token']);
            $province_token = provinceInfo::where('province_id', $dhoInfo->district->province_id)->get()->pluck(['token']);
            $district_token = [];
            $municipality_token = MunicipalityInfo::where('district_id', $dhoInfo->district_id)->get()->pluck(['token']);
            $hospital_or_cict_tokens = Organization::where('district_id', $dhoInfo->district_id)->get()->pluck(['token']);
        }if ($user->role == 'municipality') {
                $municipalityInfo = MunicipalityInfo::where('token', $user->token)->first();
                $labs_token = OrganizationMember::where('role', '=', 'fchv')->where('municipality_id', '=', $municipalityInfo->municipality_id)->get()->pluck(['token']);
                $province_token = provinceInfo::where('province_id', $municipalityInfo->province_id)->get()->pluck(['token']);
                $district_token = DistrictInfo::where('district_id', $municipalityInfo->district_id)->get()->pluck(['token']);
                $hospital_or_cict_tokens = Organization::where('district_id', $municipalityInfo->municipality_id)->get()->pluck(['token']);
                $municipality_token = [];
                $center_tokens = [];
    }
        if ($user->role == 'healthpost') {
            $healthpostInfo = Organization::where('token', $user->token)->first();
            $hospital_or_cict_tokens = Organization::where('municipality_id', $healthpostInfo->municipality_id)->get()->pluck(['token']);
            $labs_token = OrganizationMember::where('role', '=', 'fchv')->get()->pluck(['token']);
            $province_token = [];
            $district_token = DistrictInfo::where('district_id', $healthpostInfo->district_id)->get()->pluck(['token']);
            $municipality_token = MunicipalityInfo::where('municipality_id', $healthpostInfo->municipality_id)->get()->pluck(['token']);
            $center_tokens = [];
        }
        if ($user->role == 'healthworker'){
            $find_user = OrganizationMember::where('token', $user->token)->first();
            $center_tokens = [];
            $district_token = DistrictInfo::where('district_id', $find_user->district_id)->get()->pluck(['token']);
            $municipality_token = MunicipalityInfo::where('municipality_id', $find_user->municipality_id)->get()->pluck(['token']);
            $hospital_or_cict_tokens = Organization::where('municipality_id', $find_user->municipality_id)->get()->pluck(['token']);
            $labs_token = OrganizationMember::where('role', 'fchv')->get()->pluck(['token']);
            $province_token = [];
        }


//        $users = User::where('id', '!=', Auth::id())->get();

        $users = User::all()->sortBy('username');

        $lab_users = $users->whereIn('token', $labs_token);
        $province_users = $users->whereIn('token', $province_token);
        $district_users = $users->whereIn('token', $district_token);
        $municipality_users = $users->whereIn('token', $municipality_token);
        $hospital_or_cict_users = $users->whereIn('token', $hospital_or_cict_tokens);
        $center_users = $users->whereIn('token', $center_tokens);
        $main_users = $users->where('role', 'main');

        return view('messenger.create', compact('lab_users', 'main_users','province_users','district_users', 'municipality_users', 'hospital_or_cict_users', 'center_users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        $input = Request::all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);
        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients
        if (Request::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        return redirect()->route('messages');
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
            return redirect()->route('messages');
        }

        $thread->activateAllParticipants();

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => Request::input('message'),
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Request::has('recipients')) {
            $thread->addParticipant(Request::input('recipients'));
        }

        return redirect()->route('messages.show', $id);
    }
}