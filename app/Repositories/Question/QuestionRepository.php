<?php
namespace App\Repositories\Question;

use App\Models\Question;
use App\Models\SessionUser;
use App\Repositories\Repositories;
use http\Env\Request;
use Illuminate\Validation\Rule;
use Exception;
use Cache;
use function Ramsey\Uuid\Generator\timestamp;

class QuestionRepository extends Repositories implements IQuestionRepository
{
    private $question;
    function __construct(Question $question)
    {
        $this->question = $question;
    }
    public function find($id){
        return $this->question->find($id);
    }
    public function getListQuesttion($request, $status)
    {
        if($status == 'hide'){
            $questions = $this->question->where('status', 0);
        }elseif($status=='active'){
            $questions = $this->question->where('status', 1);
        }else{
            $questions = $this->question;
        }
        $search = '';
        if($request->keyword){
            $search = $request->keyword;
            $questions = $questions->where('title', 'like', "%{$search}%");
        }
        $questions = $questions
            ->join('users', 'questions.user_id', 'users.id')
            ->select('questions.id', 'questions.title','users.name as user_name', 'questions.created_at', 'questions.status')
            ->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);

        return view('admin.question.index', compact("questions", "count", "list_act"));
    }

    public function viewQuestion($id){
            $question = $this->question->find($id);
            return $question;
    }
    public function changeStatus($id, $request){
        $question = [
          'status'=> $request->status
        ];
        $old = $this->question->find($id);
        return $old->update($question);
    }

    public function count()
    {
        $count = [];
        $count['all_question'] = $this->question->count();
        $count['question_active'] = $this->question->where('status', 1)->count();
        $count['question_hide'] = $this->question->where('status', 0)->count();
        return $count;
    }


    public function total(){

    }

    //API

    public function getAllQuestion(){
        $questions = question::
            join("users", "users.id", "questions.user_id")
            ->select('questions.id','questions.title', 'questions.content', 'questions.number_of_views', 'questions.number_of_replies','users.avatar', 'users.name as user_name', 'questions.created_at')
            ->orderBy('questions.id')
            ->get();
        return $questions;
    }
    public function ask($request)
    {
        $user = SessionUser::where('token', $request->header('token'))
            ->select('user_id')
            ->first();
        $question = [
            "title"=> $request->title,
            "content" => $request->content,
            "user_id" => $user->user_id,
            "status" => 1,
        ];
        $question = $this->question->create($question);
        return $question;
    }
    public function getQuestionByUserId($user_id){
        $questions = question::
        select('id', 'title', 'content', 'number_of_views', 'number_of_replies')
            ->where('user_id', $user_id)
            ->orderBy('number_of_views')
            ->get();
        return $questions;
    }
    public function getQuestion($id){
        $question = $this->question->find($id);
        return $question;
    }
}
