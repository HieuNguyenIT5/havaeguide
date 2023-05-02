<?php

namespace App\Repositories\Question;

interface IQuestionRepository{
    public function getListQuesttion($request, $status);
    public function viewQuestion($id);
    public function changeStatus($id, $request);

    //Api
    public function getAllQuestion();
    public function ask($request);
    public function getQuestionByUserId($user_id);
    public function getQuestion($id);

}
