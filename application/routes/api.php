<?php

use App\Stream;
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
    Route::get('/my_form_performace', 'ReportingController@index');
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
    Route::get('study_users', function(){

        $user =  Auth::id();
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


    Route::get('/getFormUser', 'UserController@getFormUser');
    Route::get('/getGlobalSiteConfig/{id?}', function ($id = null) {
        $Cms_form_config =  DB::table('cms_form_configs')->where([['study_id' , '=', $id]])->get();
        return $Cms_form_config;
    });

});


Route::middleware('auth:api')->group(function () {
    Route::post('/invite', 'AccessController@store');
});

Route::get('linkChecker/{id}', 'AccessController@linkChecker');



Route::post('foo/{id}', function (Request $request, $id) {

// update json
    $affected = DB::table('streams')
        ->where('studyId', $id)
        ->update(['questions->sort_order' => null]);


    DB::table('streams')
        ->where('studyId', $id)
        ->where('questions->question_uniqid', 'page-break')
        ->delete();






    $array = json_decode(json_encode($request->post()), true);

    foreach ($array as $key => $value) {
        if ($value['question_uniqid'] == 'page-break') {
            $flight = new Stream;
            $unique_id = uniqid();

            $in = array("name" => "pb", "type" => "pagebreak", "sort_order" => $value['order'], "question_uniqid" => "page-break", "required" => false, "answer" => null, "key" => null);
            $flight->questions = json_encode($in);
            $flight->studyId = $id;
            $flight->question_uniqid = $unique_id;
            $flight->save();
        } else {

           Stream::where('questions->question_uniqid', $value['question_uniqid'])
                ->update(['questions->sort_order' => $value['order']]);
        }
    }
//

    print_r($array);
});





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
