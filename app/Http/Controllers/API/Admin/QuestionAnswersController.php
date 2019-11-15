<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\QuestionAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionAnswersController extends BaseController
{

    /**
     * Get all Q&A
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return $this->sendResponse(
            QuestionAnswer::all()
            , 'Successfully retrieved all Q&A');
    }

    /**
     * Add new q&a
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store(){
        $validator = Validator::make(request()->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'package_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }
        $incomingData = request()->all();
        $incomingData['created_at'] = Carbon::now();
        $incomingData['updated_at'] = Carbon::now();
        QuestionAnswer::create($incomingData);
        return $this->sendResponse([], 'Successfully created the Q & A');
    }

    /**
     * Update Q&A
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function update(){
        $validator = Validator::make(request()->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'package_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please provide valid data', ['error'=>$validator->errors()], 422);
        }

        $incomingData = [
            'question' => request('question'),
            'answer' => request('answer'),
            'package_id' => request('package_id'),
            'updated_at' => Carbon::now()
        ];

        $questionAnswer = QuestionAnswer::where('id', request('id'))->update($incomingData);
        return $this->sendResponse([], 'Q&A updated successfully.');
    }

}
