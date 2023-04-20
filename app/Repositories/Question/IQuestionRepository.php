<?php

namespace App\Repositories\Question;

interface IQuestionRepository{
    public function getListQuesttion($request, $status);
    public function createQuestion();
    public function storeQuestion($request);
    public function editQuestion($id);
    public function updateQuestion($request, $id);
    public function removeQuestion($request);
    public function deleteQuestion($request);
    public function count();
    public function total();

    //Api 
    public function getAllQuestion();
}