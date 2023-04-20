<?php
namespace App\Repositories\Question;

use App\Models\Question;
use App\Repositories\Repositories;
use Illuminate\Validation\Rule;
use Exception;
use Cache;

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
        $questions = $this->getModelWithStatus($status, $this->question);
        $search = '';
        if($request->keyword){
            $search = $request->keyword;
            $questions = $questions->where('title', 'like', "%{$search}%");
        }
        $questions = $questions->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);

        return view('admin.question.index', compact("questions", "count", "list_act"));
    }

    public function createQuestion(){
        return view("admin.question.create", compact("sectors"));
    }

    public function storeQuestion($request){
        $request->validate(
            [
                'question_name'=> 'required|string|max:200',
            ],
            [
                'required'=> ':attribute không được bỏ trống!',
                'max'=> ':attribute có độ dài lớn nhất :max ký tự!',
            ],
            [
                'question_name'=> "Tên ngành",
            ]
        );
        // dd($request->all());
        try{
            $question = $request->except('_token');
            $this->question->create($question);
                return redirect()->back()->with("success", "Thêm ngành đào tạo thành công!");
        }catch(Exception $ex){
            return redirect()->back()->with("danger", "Thêm ngành đào tạo thất bại!".$ex->getMessage());
        }
    }

    public function editQuestion($id){
        $question = $this->find($id);
        return compact("Sectors", "question");
    }
    public function updateQuestion($request, $id){
        $rules = [
            'question_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('questions')->ignore($id),
            ],
            'question_name' => 'required|string|max:200',
            'question_address' => 'string|max:500',
            'question_phone' => [
                'required',
                'string',
                'regex:/^0[0-9]{9,10}$/',
                'max:11',
            ],
            'question_image' => 'mimes:jpg,png,gif,webp|max:20000',
        ];

        $messages = [
            'required' => ':attribute không được bỏ trống!',
            'max' => ':attribute có độ dài lớn nhất :max ký tự!',
            'email' => 'không đúng định dạng!',
            'unique' => ':attribute đã được sử dụng',
            'mimes' => ':attribute phải có định dạng :mimes!',
        ];

        $attributes = [
            'question_code' => 'Mã trường',
            'question_email' => 'Email',
            'question_name' => 'Tên trường',
            'question_address' => 'Địa chỉ',
            'question_phone' => 'Số điện thoại',
            'question_image' => 'Logo',
        ];

        if ($request->question_email != null) {
            $rules['question_email'] = [
                'max:255',
                Rule::unique('questions')->ignore($id),
            ];
        }

        $request->validate($rules, $messages, $attributes);

        try {
            $question = $request->except('_token');
            if ($request->hasFile('question_image')) {
                $image = $request->file('question_image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $fileName);
                $question['question_image'] = $fileName;
            }

            $this->question->find($id)->update($question);

            return redirect("admin/question")->with("success", "Cập nhật thông tin trường học thành công!");
        } catch(Exception $ex) {
            return redirect("admin/question")->with("danger", "Cập nhật thông tin trường học thất bại! " . $ex->getMessage());
        }
    }

    public function removeQuestion($id){
        try{
            $question = $this->question->withTrashed()->find($id);
            if($question != null){
                $question->delete();
                return redirect()->back()->with('success', "Ẩn ngành đào tạo thành công!");
            }
            return redirect()->back()->with('danger', "Không có ngành đào tạo nào như thế!");
        }catch(Exception $e){
            return redirect()->back()->with('success', "Ẩn ngành đào tạo thất bại!");
        }
    }
    public function restoreQuestion($id){
        try{
            $question = $this->question->withTrashed()->find($id);
            if($question != null){
                $question->restore();
                return redirect()->back()->with('success', "Hiển thị ngành đào tạo thành công!");
            }
            return redirect()->back()->with('danger', "Không có ngành đào tạo nào như thế!");
        }catch(Exception $e){
            return redirect()->back()->with('success', "Hiển thị ngành đào tạo thất bại!");
        }
    }
    public function deleteQuestion($id){
        try{
            $question = $this->question->withTrashed()->find($id);
            if($question != null){
                $question->forceDelete();
                return redirect()->back()->with('success', "Xóa ngành đào tạo thành công!");
            }
            return redirect()->back()->with('danger', "Không có ngành đào tạo nào như thế!");
        }catch(Exception $e){
            return redirect()->back()->with('success', "Xóa ngành đào tạo thất bại!");
        }
    }
    public function count()
    {
        $count = [];
        $count['all_question'] = $this->question->withTrashed()->count();
        $count['question_active'] = $this->question->all()->count();
        $count['question_hide'] = $this->question->onlyTrashed()->count();
        return $count;
    }


    public function total(){

    }

    //API

    public function getAllQuestion(){
        $questions = Cache::get('questions');
        if($questions == null){
            $questions = question::select('id', 'question_name', 'sector_id')->get();
            Cache::put('questions',$questions, 86400);
        }
        return $questions;
    }
}
