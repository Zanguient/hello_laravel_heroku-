<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\UserProfile;
use Illuminate\Support\Facades\DB;
use App\Services\ProfileUserService;

class UserProfileController extends Controller
{
    private $profileUserService;

    public function __construct(ProfileUserService $profileUserService)
    {
        $this->profileUserService = $profileUserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      return   UserProfile::where('user_id', Auth::user()->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * {
    "gender": "",
    "agegroup": "",
    "location": "",
    "bio": "",
    "port_id": "",
    "ethnicity": "",
    "sectors": [
    {
    "key": 32,
    "station": "Eureka",
    "state": "CO"
    }
    ],
    "contactNumber": ""
    } "INVALID"
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user_id =  Auth::user()->id;
        $portal_id =  Auth::user()->portal_id;

        DB::table('user_profiles')
            ->updateOrInsert(
                ['user_id' => $user_id, 'portal_id' => $portal_id],
                ['gender' => $request->gender,
                    'agegroup' => $request->agegroup,
                    'location' => $request->location,
                    'bio' => $request->bio,
                    'ethnicity' => $request->ethnicity,
                    'sectors' =>   trim(json_encode($request->sectors)),
                    'contactnumber' =>   $request->contactnumber,
                    'profile_status' => (boolean) $request->profile_status,
                    'newsletter_subscription' =>  (boolean) $request->newsletter
                ]
            );


return array('redirect_url' => '/home/listing');

        return $request->post();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Find potential survey participants.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function participantFinder(Request $request)
    {
        //


$study_id = $request->post('studyId');
$request=  $request->post('sectors');

        $collection = collect($request);

        $plucked = $collection->pluck('name');

   // return    $plucked =   $plucked->all();




        //return print_r($request->all());
        // $porfile = $request->post('sectors',true);

        //$porfile = array('profile_status' => true, 'contactnumber' => 447834077855);

       // $users = UserProfile::where('sectors->state','=','CO')->select('*')->get();

        $participant_shortlist = DB::table('participant_shortlist')
            ->select('member_id as user_id')
            ->where('study_id', $study_id)->get();


 //return $participant_shortlist;

        $users = DB::table('user_profiles')
           // ->join('accesses', 'user_profiles.user_id', '=', 'accesses.user_id')
           ->leftJoin('users', 'user_profiles.user_id', '=', 'users.id')
            ->leftJoin('participant_shortlist', function ($join) use ($study_id) {
                $join->on('user_profiles.user_id', '=', 'participant_shortlist.member_id')->where('study_id', $study_id);
            })
             ->whereIn('user_profiles.sectors->state', $plucked)
            ->get();

        //return count($users); // if 0  do something
        return $users;

        // return json_decode($users[0]->sectors);
//        return  UserProfile::all();

/*  {
        "id": 3,
        "user_id": 1,
        "portal_id": 1,
        "gender": "Male",
        "agegroup": "41 - 50",
        "location": "London",
        "bio": "I live and work in London as a web applications developer.",
        "ethnicity": "Black",
        "sectors": "{\"key\": 2, \"state\": \"Accountancy\", \"station\": \"Big Horn\"}",
        "contactnumber": "447834077855",
        "profile_status": true,
        "updated_at": null,
        "created_at": null,
        "newsletter_subscription": true
    }*/
    }
}
