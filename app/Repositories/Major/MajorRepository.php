<?php
namespace App\Repositories\Major;

use App\Models\Major;
use App\Models\Sector;
use App\Repositories\Repositories;
use Illuminate\Validation\Rule;
use Exception;
use GuzzleHttp\Psr7\Message;

class MajorRepository extends Repositories implements IMajorRepository
{
    private $Major;
    private $Sector;
    function __construct(Major $Major,Sector $Sector)
    {
        $this->Major = $Major;
        $this->Sector = $Sector;
    }
    public function find($id){
        return $this->Major->find($id);
    }
    public function getMajor($request, $status)
    {
        $Majors = $this->getModelWithStatus($status, $this->Major)->join('sectors', 'sectors.id', '=', 'majors.sector_id')->select('majors.*', 'sectors.name');
        $search = '';
        if($request->keyword){
            $search = $request->keyword;
            $Majors = $Majors->where('major_name', 'like', "%{$search}%");
        }
        $Majors = $Majors->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);
        
        return view('admin.major.index', compact("Majors", "count", "list_act"));
    }
    
    public function createMajor(){
        $Sectors = $this->Sector->select("id", "name")->get();
        return view("admin.major.create", compact("Sectors"));
    }

    public function storeMajor($request){
        $request->validate(
            [
                'major_name'=> 'required|string|max:200',
            ],
            [
                'required'=> ':attribute không được bỏ trống!',
                'max'=> ':attribute có độ dài lớn nhất :max ký tự!',
            ],
            [
                'major_name'=> "Tên ngành",
            ]
        );
        // dd($request->all());
        try{
            $Major = $request->except('_token');
            if(!empty($request->file('image'))) {
                $fileName = time() . '.' . $request->image->extension();
                $request->image->move(public_path("images"), $fileName);
                $Major['Major_image'] = $fileName;
            }else $Major['Major_image'] = 'image_blank.jpg';
            $this->Major->create();
                return redirect("admin/Major")->with("success", "Thêm trường học thành công!");
        }catch(Exception $ex){
            return redirect("admin/Major")->with("danger", "Thêm trường học thất bại!".$ex->getMessage());
        }
    }

    public function editMajor($id){
        $Sectors = $this->Sector->select("id", "Sector_name")->get();
        $Major = $this->find($id);
        return compact("Sectors", "Major");
    }
    public function updateMajor($request, $id){
        $rules = [
            'Major_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('Majors')->ignore($id),
            ],
            'Major_name' => 'required|string|max:200',
            'Major_address' => 'string|max:500',
            'Major_phone' => [        
                'required',        
                'string',        
                'regex:/^0[0-9]{9,10}$/',        
                'max:11',    
            ],
            'Major_image' => 'mimes:jpg,png,gif,webp|max:20000',
        ];
        
        $messages = [
            'required' => ':attribute không được bỏ trống!',
            'max' => ':attribute có độ dài lớn nhất :max ký tự!',
            'email' => 'không đúng định dạng!',
            'unique' => ':attribute đã được sử dụng',
            'mimes' => ':attribute phải có định dạng :mimes!',
        ];
        
        $attributes = [
            'Major_code' => 'Mã trường',
            'Major_email' => 'Email',
            'Major_name' => 'Tên trường',
            'Major_address' => 'Địa chỉ',
            'Major_phone' => 'Số điện thoại',
            'Major_image' => 'Logo',
        ];
        
        if ($request->Major_email != null) {
            $rules['Major_email'] = [
                'max:255',
                Rule::unique('Majors')->ignore($id),
            ];
        }
        
        $request->validate($rules, $messages, $attributes);
    
        try {
            $Major = $request->except('_token');
            if ($request->hasFile('Major_image')) {
                $image = $request->file('Major_image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $fileName);
                $Major['Major_image'] = $fileName;
            }
            
            $this->Major->find($id)->update($Major);
    
            return redirect("admin/Major")->with("success", "Cập nhật thông tin trường học thành công!");
        } catch(Exception $ex) {
            return redirect("admin/Major")->with("danger", "Cập nhật thông tin trường học thất bại! " . $ex->getMessage());
        }
    }
    
    public function removeMajor($request){

    }
    public function deleteMajor($request){

    }
    public function count()
    {
        $count = [];
        $count['all_major'] = $this->Major->withTrashed()->count();
        $count['major_active'] = $this->Major->all()->count();
        $count['major_hide'] = $this->Major->onlyTrashed()->count();
        return $count;
    }


    public function total(){

    }
}