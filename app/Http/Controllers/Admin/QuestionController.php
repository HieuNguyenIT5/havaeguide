<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Question\IQuestionRepository;

class QuestionController extends Controller
{
    private IQuestionRepository $questionRepo;
    public function __construct(IQuestionRepository $questionRepo){
        $this->questionRepo = $questionRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'question']);
            return $next($request);
        });
    }
    
    public function index(Request $request, $status = '')
    {
        return $this->questionRepo->getListQuesttion($request, $status);
    }
}
