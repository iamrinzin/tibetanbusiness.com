<?php

namespace App\Http\Controllers\Job;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Job\JobAnswerResource;
use App\Http\Resources\Job\JobBasicInfoResource;
use App\Http\Resources\Job\JobQuestionResource;
use App\Job\JobAnswer;
use App\Job\JobBasicInfo;
use App\Job\JobQuestion;
use App\User;
use Illuminate\Support\Facades\Auth;

class JobQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(JobBasicInfo $jobBasicInfo)
    {
        return $jobBasicInfo->job_questions()->paginate('1'); 
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
        // return $request;
        $question = JobQuestion::create([
            'user_id' => Auth::user()->id,
            'job_basic_info_id' => $request->job_basic_info_id,
            'question' => $request->question,
            'name' => $request->name,
            'avatar' => $request->avatar,
        ]);
        return $question;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(JobBasicInfo $jobBasicInfo)
    {
        return $jobBasicInfo->job_questions()->paginate('2');

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

    // All comments
    public function view(JobQuestion $jobQuestion,$id){
        return $jobQuestion->replies;
    }
    // Questions
    public function question(JobBasicInfo $jobBasicInfo){
        return $jobBasicInfo->job_questions()->paginate('1'); 

        // $questions = JobQuestion::where('job_basic_info_id', '=', "$id")
        //     ->orderBy('created_at', 'desc')->paginate(3);
        // return $questions->toArray($questions);
    }

    public function replies($id)
    {
        return new JobQuestionResource(JobQuestion::find($id));

    }
}
