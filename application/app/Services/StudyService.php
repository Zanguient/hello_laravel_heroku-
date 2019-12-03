<?php

namespace App\Services;

use App\Repositories\StudyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StudyService
{
    public function __construct(StudyRepository $studyRepository)
    {
        $this->study = $studyRepository;
    }

    public function index()
    {

       // Cache::put('Glen', 'Champ', $seconds);

//        $value = Cache::get('Glendo', function () {
//            $seconds  = 1000;
//            $value  =  $this->study->all();
//            Cache::put('Glendo',  $value, $seconds);
//            return  $value;
//        });
        $value  =  $this->study->all();
        return  $value;
    }

    public function create_new(Request $request)
    {
        $stream['name'] = $request->name;
        $stream['description'] = $request->description;
        $stream['description_short'] = $request->description_short;
        $stream['invite_code'] = $request->invite_code;
        $stream['start_date'] = $request->start_date;
        $stream['end_date'] = $request->end_date;

        return $this->study->create($stream);
    }

    public function update($request, $id)
    {
            unset($request['descriptiont']);
            $attributes = $request;

        return $this->study->update($id, $attributes);

    }

    public function findStudyByName(Request $request)
    {
        return $this->study->where_study_name($request->name);
    }

    public function findStudyById(int $id)
    {
        return $this->study->where_study_id($id);
    }

}
