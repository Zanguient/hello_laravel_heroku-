<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Validator;
use App\Custom_message;

class CustomMessageController extends Controller
{

    public $sucessStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //



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
        $id  = $request->post('study_id');
        $validator = Validator::make($request->all(),[
            'label' => [
                'required',
            Rule::unique('custom_messages')->where(function($query) use ($id) {
                $query->where('study_id', '=',  $id);
            }),
            ],
            'message' => 'required',
            'study_id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()],401);
        }

        $input = $request->all();
        $input['unique_id'] =  uniqid();
        $user = Custom_message::create($input);

        return response()->json(['success' => $input], $this->sucessStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messages = Custom_message::where('study_id', $id)->get();

        return response()->json($messages);
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
        $request = $request->post();
        DB::table('custom_messages')
            ->where('id', $id)
            ->update($request);

        return $request;

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
    }  /**
     * Gets Drag and Drop Messages Ids.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function draganddropelemetns($id)
    {
        //draganddropelemetns

        // $messages = Custom_message::where('study_id', $id)->get();

         $messages =    Custom_message::select("label", DB::raw("CONCAT('message_',custom_messages.unique_id) as question_uniqid"))->get();

        return response()->json($messages);
    }


}
