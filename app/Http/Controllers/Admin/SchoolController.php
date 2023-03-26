<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\School\ISchoolRepository;
use App\Models\School;
use Illuminate\Http\Request;
use App\Imports\ExcelImports;
use App\Exports\ExcelExports;
use Excel;

class SchoolController extends Controller
{
    private ISchoolRepository $schoolRepo;

    function __construct(ISchoolRepository $schoolRepo)
    {
        $this->schoolRepo  = $schoolRepo;
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'school']);
            return $next($request);
        });
    }

    public function Index(Request $request, $status = '')
    {
        return $this->schoolRepo->getSchool($request, $status);
    }

    public function Create()
    {
        return $this->schoolRepo->createSchool();
    }

    public function Store(Request $request)
    {
        return $this->schoolRepo->storeSchool($request);
    }

    public function import(Request $request)
    {
        $path = $request->file('file_import')->getRealPath();
        Excel::import(new ExcelImportSchools, $path);
        return redirect("admin/school")->with('success', 'Thêm thành công!');
    }

    public function Edit($id)
    {
        $compact = $this->schoolRepo->editSchool($id);
        return view("admin.school.edit", $compact);
    }
    public function Update(Request $request, $id)
    {
        return $this->schoolRepo->updateSchool($request, $id);
    }

    public function excelExport(){
        return Excel::download(new ExcelExportSchools , 'school.xlsx');
    }
}
