<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\TestDetails;
Use App\TestQuestion;

class TestDetailController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','sendOTP','verifyOTP','sendOTPlogin','create_quiz','addQuestion','getTestDetails','getTestList','getQuestionList']]);
    }
    public function create_quiz(Request $request) {
        error_log('in create test');
        error_log($request->test_name);
        $quiz = TestDetails::create($request->all());
        $test_id = $quiz->id;
        return response()->json(["test_id" => $test_id, $quiz]);

    }

    public function addQuestion(Request $request) {
        $question = TestQuestion::create($request->all());
        $number_of_question_added = TestQuestion::where('test_id',$question->test_id)->count();
        $number_of_question = TestDetails::where('test_id',$question->test_id)->pluck('no_of_question');
        error_log($number_of_question);
        return response()->json(["number_of_question_added"=> $number_of_question_added, "number_of_question"=> $number_of_question, $question]);
    }

    public function getTestDetails(Request $request, $test_id) {
        $testdetails = TestDetails::where('test_id',$test_id)->get();

        $tesquestionlist = TestQuestion::where('test_id',$test_id)->get();
        return response()->json(["testdeatils"=> $testdetails, "questionlist"=> $tesquestionlist]);
    }

    public function getTestList(Request $request) {
        error_log('in post question');
        error_log($request->class);
        $testdetails=TestDetails::where('class',$request->class)
         ->where('subject',$request->subject)
         ->where('board',$request->board)->get();
         if(empty($testdetails))
         {
             return('no test found');
         }
         return response()->json(["testdeatils"=> $testdetails]);
        

    }

    public function getQuestionList(Request $request) {
        error_log('in post question');
        error_log($request->test_id);
        $testquestion=TestQuestion::where('test_id',$request->test_id)->get();
         if(empty($testquestion))
         {
             return('no question found');
         }
         return response()->json(["question"=> $testquestion]);
        

    }


}
