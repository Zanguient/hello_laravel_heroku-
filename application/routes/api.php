<?php

use App\Custom_message;
use App\Stream;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');



// Reports

Route::middleware('auth:api')->group(function () {



    Route::get('user', function () {

        // return  $this->user;

        return DB::table('users')->paginate(10    );
    });


    Route::get('/my_form_performace', 'ReportingController@index');
    Route::post('registerPortalUser', 'PassportController@registerPortalUser');
});

// study

Route::middleware('auth:api')->group(function () {






    Route::post('/studies', 'StudyController@update');

    Route::post('/createStudy', 'StudyController@create');
    Route::post('/addStudyItem/{id}', 'StudyController@addStudyItem');
    Route::post('/studyItemUpdate/{id}', 'StudyController@studyItemUpdate');
    Route::post('/study_item_access/{id}/{study_id}', 'StudyController@study_item_access');

    Route::get('/studies', 'StudyController@index');

    Route::get('/getStudyItem/{id}', 'StudyController@studyItem');
    Route::get('/studies/{id}', 'StudyController@edit');
    Route::get('/studyItemListing/{id}', 'StudyController@studyItemListing');
    Route::get('/study_users/{id}/{studyitem}', 'StudyController@study_users');
    Route::get('/study_users_form_populators/{id}', 'StudyController@study_users_form_populators');

    Route::get('/formGetStudies', 'StudyController@formGetStudies');
    Route::get('study_users', function () {

        $user = Auth::id();
        return $user;
    });
});

//  streams
Route::middleware('auth:api')->group(function () {
    Route::post('/createStream', 'StreamController@create');
    Route::post('/getStudyQuestions', 'StreamController@studyQuestions');
    Route::post('/saveEditStudyField', 'StreamController@update');

    Route::get('/questionstream/{question_uniqid?}', 'StreamController@show');
});
//forms
Route::middleware('auth:api')->group(function () {
    Route::post('/saveForLater/{formid}', 'FormController@store');
    Route::post('/saveForm/{formid}/{study_id?}', 'FormController@saveForm');
    Route::post('/globalsiteconfig/', 'FormController@globalSiteConfig');

    Route::get('/questionstreams', 'FormController@index');
    Route::get('/formStudyItemListings/{id}', 'StudyController@formStudyItemListings');
    Route::get('/formStudyItemListing/{id}', 'StudyController@formStudyItemListing');
    Route::get('/questionstreams/{id}', 'FormController@show');
    Route::get('/getFormValues/{id}', 'FormController@getFormValues');


// Messages
    Route::post('/create_custom_message', 'CustomMessageController@store');
    Route::get('/custom_message/{id}', 'CustomMessageController@show');
    Route::post('/update_custom_message/{id}', 'CustomMessageController@update');
    Route::get('/study_message/{id}', 'CustomMessageController@draganddropelemetns');


// Users

    Route::get('studySectors', function () {
        $jsonReques = DB::table('study_sectors')->get();
        return response()->json($jsonReques);
    });


    Route::post('shortlist', function (Request $request) {


        $study_id = $request->post('id');


        $users = DB::table('user_profiles')



            ->leftJoin('users', 'user_profiles.user_id', '=', 'users.id')



            ->Join('participant_shortlist', function ($join) use ($study_id) {
                $join->on('users.id', '=', 'participant_shortlist.member_id')
                    ->where('participant_shortlist.study_id', $study_id);
            })


            //  ->whereIn('user_profiles.sectors->state', $plucked)

            ->get();

        return $users;


    });

    Route::post('participantFinder', 'UserProfileController@participantFinder');


    Route::post('inviteRegisteredMember/{study_id}', 'AccessController@inviteRegisteredMember'); // participant_shortlist
    Route::post('quickInviteRegisteredMember/{study_id}', 'AccessController@quickInviteRegisteredMember');
    Route::post('removeParticipantFromShortList/{study_id}', 'AccessController@removeParticipantFromShortList');



    Route::post('fake/{user_id}/{portal}', function (Request $request, $user_id, $portal) {
//SQL
        /*
         * check if user is auth and has access level  of 2
         * check if user  being  updated belongs  to portal and if user has access level between 2 and 1 and user  is active
         * get all study ids belonging to  updaing user and compare  to submittted list and remove thoes that  dont match and insert ones that  do
         * */

        $collection = collect($request);
        $selection = $collection->pluck('id');
        $userDetails = DB::select('select u.id, u.email
            from users u
            where u.id =' . $user_id . ' 
            and u.access_level between 1 and 2 
            and u.portal_id = ' . $portal
        );

        // return   $userDetails[0]->email;
        if ($userDetails) {
            $upd = DB::table('accesses')
                ->where('user_id', $user_id)
                ->where('active', 1)
                ->update(['active' => 0]
                );
            if ($selection) {
                // return $selection;
                foreach ($selection as $value) {
                    DB::table('accesses')->insert([
                        ['invitee_email' => $userDetails[0]->email, 'active' => 1, 'study_id' => $value, 'user_id' => $user_id]
                    ]);
                }
            }
        }
    });


    Route::get('/getFormUser', 'UserController@getFormUser');

    Route::resource('users', 'UserController');
    Route::resource('profile', 'UserProfileController');


    Route::get('assignedStudies/{id}', function ($id) {

        $user = Auth::id();
        return DB::select('select s.name, s.id
                from accesses a
                join studies s on a.study_id = s.id
                where a.user_id=' . $id . '
                and a.active = 1
                AND s.portal_id = 1'
        );
    });

    Route::get('unassignedStudies/{id}', function ($id) {
        $user = Auth::id();
        return DB::select('select studies.name, studies.id  from studies
            WHERE portal_id in (select
                CASE
                WHEN portal_id is not null THEN portal_id
                ELSE id
                END AS portal_id
            from users
            -- where id =' . $user . '
            where access_level = 2
            order by id desc
            LIMIT 1)
           -- AND id not in (
          --  select  s.id
          --  from accesses
          --  join studies s on accesses.study_id = s.id
          --  where accesses.user_id=' . $id .
            '
             -- )'
        );

    });


// Global site config
    Route::get('/getGlobalSiteConfig/{id?}', function ($id = null) {
        $Cms_form_config = DB::table('cms_form_configs')->where([['study_id', '=', $id]])->get();
        return $Cms_form_config;
    });

});

// Invite
Route::middleware('auth:api')->group(function () {
    Route::post('/invite', 'AccessController@store');
});


Route::get('linkChecker/{id}', 'AccessController@linkChecker');

Route::middleware('auth:api')->group(function () {
    Route::get('delete', function () {
        $access_level =
            DB::table('users')->
            select(DB::raw('portal_id as portal_identity,
       CASE
WHEN portal_id is not null THEN portal_id
ELSE id
           END AS portal_id
'))
                ->whereIn('id', [Auth::id()])
                ->where('access_level', 2)
                ->limit(1)
                ->orderBy('id', 'desc')
                ->get();
        return $access_level[0]->portal_id;
    });


// DASHBOARD
    Route::get('registeredUsers', function () {
//return  Auth::user();
        //$custom_message = User::where([['access_level', 256],['portal_id', Auth::user()->portal_id]])->count('id');

        $access_level =
        $registeredUsersCount =
            DB::select('select distinct  count(user_id) from accesses
where study_id in (select s.id  from studies s where portal_id=1)
and user_id is not null');

        return $registeredUsersCount;
    });
    Route::get('premiumUsers', function () {

        $custom_message = User::where('access_level', 2)->count('id');
        return $custom_message;
    });
});
//DASHBOARD END


// SAVE DRAG AND DROP ORDER QUESTIONS
Route::post('foo/{id}', function (Request $request, $id) {


// update json
    $affected = DB::table('streams')
        ->where('studyId', $id)
        ->update(['questions->sort_order' => null]);


    DB::table('streams')
        ->where('studyId', $id)
        ->where('questions->question_uniqid', 'page-break')
        ->orWhere('questions->type', 'message-box')
        ->where('studyId', $id)
        ->delete();

    $array = json_decode(json_encode($request->post()), true);

    foreach ($array as $key => $value) {
        $message_question_uniqid = explode('_', $value['question_uniqid']);

        // file_put_contents('glendelete.txt', $message_question_uniqid[0], FILE_APPEND);
        if ($value['question_uniqid'] == 'page-break') {
            $flight = new Stream;
            $unique_id = uniqid();

            $in = array("name" => "pb", "type" => "pagebreak", "description" => "Page Break", "sort_order" => $value['order'], "question_uniqid" => "page-break", "required" => false, "answer" => null, "key" => null);
            $flight->questions = json_encode($in);
            $flight->studyId = $id;
            $flight->question_uniqid = $unique_id;
            $flight->save();
        } elseif ($message_question_uniqid[0] == 'message') {

            //return $message[1];

            $custom_message = Custom_message::where('unique_id', $message_question_uniqid[1])->select('message')->get();
            $custom_message = json_decode($custom_message, true);

            $flight = new Stream;
            $message = array("name" => "msg", "type" => "message-box", "description" => "Message", "sort_order" => $value['order'], "question_uniqid" => $value['question_uniqid'], "required" => false, "answer" => html_entity_decode($custom_message[0]['message']), "key" => null);
            $flight->questions = json_encode($message);
            $flight->studyId = $id;
            $flight->question_uniqid = $value['question_uniqid'];
            $flight->save();
        } else {

            Stream::where('questions->question_uniqid', $value['question_uniqid'])
                ->update(['questions->sort_order' => $value['order']]);
        }
    }
//

    return $array;
}
);


// SAVE DRAG AND DROP ORDER QUESTIONS EDN


//print_r($array);


//$seconds  = 10;


/*
   foreach($array as $key => $value){


        echo  $stream =  $value['sort_order'];
//$stream  = Stream::where('question_uniqid', $value);


    }
 * */


// return response()->json(us::all());


//    $value = Cache::get('Glen', function () {
//        //return DB::table(...)->get();
//    });
//return $value;
//
//    $redis = new Redis();
//
//    $redis->set('boo','Have beer and relax!');
// return $redis->get('boo');
//
//    //Redis::set('name', 'Taylor');
//
//   // $values = Redis::lrange('names', 5, 10);
//
//    echo 'Hello World! How are you';
//});
