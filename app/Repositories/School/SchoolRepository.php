<?php
namespace App\Repositories\School;

use App\Models\School;
use App\Models\SchoolType;
use App\Repositories\Repositories;
use Illuminate\Validation\Rule;
use Exception;
use GuzzleHttp\Psr7\Message;

class SchoolRepository extends Repositories implements ISchoolRepository
{
    private $school;
    private $type;
    function __construct(School $school,SchoolType $type)
    {
        $this->school = $school;
        $this->type = $type;
    }
    public function find($id){
        return $this->school->find($id);
    }
    public function getSchool($request, $status)
    {
        $schools = $this->getModelWithStatus($status, $this->school)->join('school_types', 'school_types.id', '=', 'schools.type_id')->select('schools.*', 'school_types.type_name');
        $search = '';
        if($request->keyword){
            $search = $request->keyword;
            $schools = $schools->where('school_name', 'like', "%{$search}%");
        }
        $schools = $schools->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);
        
        return view('admin.school.index', compact("schools", "count", "list_act"));
    }
    
    public function createSchool(){
        $types = $this->type->select("id", "type_name")->get();
        return view("admin.school.create", compact("types"));
    }

    public function storeSchool($request){
        $request->validate(
            [
                'school_code'=> 'required|string|unique:schools,school_code|max:10',
                'school_email'=>'required|string|email|max:255|unique:schools,school_email',
                'school_name'=> 'required|string|max:200',
                'school_address'=> 'string|max:500',
                'school_phone'=> 'string|max:10',
                'school_image'=> 'mimes:jpg,png,gif|max:20000'
            ],
            [
                'required'=> ':attribute không được bỏ trống!',
                'max'=> ':attribute có độ dài lớn nhất :max ký tự!',
                'email'=> 'không đúng định dạng!',
                'unique'=> ':attribute đã được sử dụng',
                'mimes'=> ':attribute phải có định dạng :mimes!',
            ],
            [
                'school_code'=> "Mã trường",
                'email'=>"Email",
                'school_name'=> "Tên trường",
                'school_address'=> 'Địa chỉ',
                'school_website' => 'Địa chỉ trang web',
                'school_image'=> 'logo'
            ]
        );
        // dd($request->all());
        try{
            $school = $request->except('_token');
            if(!empty($request->file('image'))) {
                $fileName = time() . '.' . $request->image->extension();
                $request->image->move(public_path("images"), $fileName);
                $school['school_image'] = $fileName;
            }else $school['school_image'] = 'image_blank.jpg';
            $this->school->create($school);
                return redirect("admin/school")->with("success", "Thêm trường học thành công!");
        }catch(Exception $ex){
            return redirect("admin/school")->with("danger", "Thêm trường học thất bại!".$ex->getMessage());
        }
    }

    public function editSchool($id){
        $types = $this->type->select("id", "type_name")->get();
        $school = $this->find($id);
        return compact("types", "school");
    }
    public function updateSchool($request, $id){
        $rules = [
            'school_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('schools')->ignore($id),
            ],
            'school_name' => 'required|string|max:200',
            'school_address' => 'string|max:500',
            'school_phone' => [        
                'required',        
                'string',        
                'regex:/^0[0-9]{9,10}$/',        
                'max:11',    
            ],
            'school_image' => 'mimes:jpg,png,gif,webp|max:20000',
        ];
        
        $messages = [
            'required' => ':attribute không được bỏ trống!',
            'max' => ':attribute có độ dài lớn nhất :max ký tự!',
            'email' => 'không đúng định dạng!',
            'unique' => ':attribute đã được sử dụng',
            'mimes' => ':attribute phải có định dạng :mimes!',
        ];
        
        $attributes = [
            'school_code' => 'Mã trường',
            'school_email' => 'Email',
            'school_name' => 'Tên trường',
            'school_address' => 'Địa chỉ',
            'school_phone' => 'Số điện thoại',
            'school_image' => 'Logo',
        ];
        
        if ($request->school_email != null) {
            $rules['school_email'] = [
                'max:255',
                Rule::unique('schools')->ignore($id),
            ];
        }
        
        $request->validate($rules, $messages, $attributes);
    
        try {
            $school = $request->except('_token');
            if ($request->hasFile('school_image')) {
                $image = $request->file('school_image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $fileName);
                $school['school_image'] = $fileName;
            }
            
            $this->school->find($id)->update($school);
    
            return redirect("admin/school")->with("success", "Cập nhật thông tin trường học thành công!");
        } catch(Exception $ex) {
            return redirect("admin/school")->with("danger", "Cập nhật thông tin trường học thất bại! " . $ex->getMessage());
        }
    }
    
    public function removeSchool($id){
        try{
            $school = $this->school->withTrashed()->find($id);
            if($school != null){
                $school->delete();
                return redirect()->back()->with('success', "Ẩn trường thành công!");
            }
            return redirect()->back()->with('danger', "Không có trường nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Ẩn trường thất bại!");
        }
    }

    public function restoreSchool($id){
        try{
            $school = $this->school->withTrashed()->find($id);
            if($school != null){
                $school->restore();
                return redirect()->back()->with('success', "Hiển thị trường thành công!");
            }
            return redirect()->back()->with('danger', "Không có trường nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Hiển thị trường thất bại!");
        }
    }

    public function deleteSchool($id){
        try{
            $school = $this->school->withTrashed()->find($id);
            if($school != null){
                $school->forceDelete();
                return redirect()->back()->with('success', "Xóa trường thành công!");
            }
            return redirect()->back()->with('danger', "Không có trường nào như thế!");
        }catch(Exception){
            return redirect()->back()->with('success', "Xóa trường thất bại!");
        }
    }
    public function count()
    {
        $count = [];
        $count['all_school'] = $this->school->withTrashed()->count();
        $count['school_active'] = $this->school->all()->count();
        $count['school_hide'] = $this->school->onlyTrashed()->count();
        return $count;
    }


    public function total(){

    }
}