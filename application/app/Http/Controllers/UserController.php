<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * regular user = 1; Super user = 2
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access_leveled =  User::where('id',  Auth::id())->select('access_level', 'portal_id')->get();
        $access_level =  $access_leveled[0]->access_level;
        $level = [8,1];
       if($access_level === 2){

           $level = [8,1,2];
        }
        return User::
            whereIn('access_level', $level)
            ->where('id', '!=', Auth::id())
            ->where('portal_id',  $access_leveled[0]->portal_id)
            ->get();

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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formUser = User::find($id);
        return response()->json($formUser);
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

//return  User::find($id);



//       echo is_array($request->post());
//
//        exit;

//        return  $userDetails;
        $selection = $request->sectors;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            // 'access_level' => 'required_if::',
           // 'suspension_note' => '',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()],401);
        }
        $rtn =  $request->post('sectors');

        $collection = collect($rtn);
        // $selection = $collection->pluck('id');

        $selection = $collection->pluck('id');



        $userDetails = DB::select('select u.id, u.email, u.portal_id
            from users u
            where u.id =' . Auth::id() . ' 
            and u.access_level between 1 and 2 
            and u.portal_id = ' . Auth::user()->portal_id
        );

        if ($userDetails) {

            $userDetails =  User::find($id);

// return jjson_decode(son_encode($selection));


            $upd = DB::table('accesses')
                ->where('user_id',  $id)
                ->where('active', 1)
                ->update(['active' => 0]
                );
            if ($selection) {
               // return $selection;
                foreach ($selection as $value) {
                    DB::table('accesses')->insert([
                        ['invitee_email' => $userDetails->email, 'active' => 1, 'study_id' => $value, 'user_id' =>  $id, 'created_by' =>  Auth::id()]
                    ]);
                }
            }
        }
       // return $selection;
        $user = User::find($id);

        $user->name = $request->name;
        $user->access_level = $request->access_level;
        $user->suspension_note = $request->suspension_note;


        $user->save();





        return response()->json('success', 200);
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

    public function getFormUser() {

     $formUser = User::find(Auth::id());
        return response()->json($formUser);
    }
}
