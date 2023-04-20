<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Repositories\Comment\ICommentRepository;
use Illuminate\Http\Request;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Sector\ISectorRepository;
use App\Repositories\School\ISchoolRepository;
use App\Repositories\Major\IMajorRepository;
use App\Repositories\SchoolType\ISchoolTypeRepository;
use App\Repositories\Page\IPageRepository;
use App\Repositories\Repositories;
use Exception;
use Illuminate\Cache\Repository;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sectorRepo;
    private $sliderRepo;
    private $schoolRepo;
    private $majorRepo;
    private $typeRepo;
    private $pageRepo;
    private $commentRepo;
    private $Repo;

    public function __construct(
        ISchoolRepository $schoolRepo,
        ISectorRepository $sectorRepo,
        SliderRepository $sliderRepo,
        Repositories $repo,
        IMajorRepository $majorRepo,
        IPageRepository $pageRepo,
        ICommentRepository $commentRepo,
        ISchoolTypeRepository $typeRepo
    )
    {
        $this->sliderRepo = $sliderRepo;
        $this->sectorRepo = $sectorRepo;
        $this->schoolRepo = $schoolRepo;
        $this->majorRepo = $majorRepo;
        $this->typeRepo = $typeRepo;
        $this->pageRepo = $pageRepo;
        $this->commentRepo = $commentRepo;

        $this->Repo = $repo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        try {
            $sliders = $this->sliderRepo->getSliderShow();
            $sectors = $this->sectorRepo->getAllSector();
            $outstandingSchools = $this->schoolRepo->getOutstendingSchools();
            $areaCenters = $this->Repo->getAreaCenter();
            return response()->json(
                [
                    "status" => 200,
                    "sliders" => $sliders,
                    "sectors" => $sectors,
                    "outstanding_schools" => $outstandingSchools,
                    "area_centers" => $areaCenters
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => 500,
                    "message" => $e->getMessage()
                ],
                500
            );
        }
    }

    public function getAllSchool($id = 0)
    {
        $sectors = $this->sectorRepo->getAllSector();
        $majors = $this->majorRepo->getAllMajor();
        $areas = $this->Repo->getArea();
        $types = $this->typeRepo->getAllType();
        $schools = $this->schoolRepo->getAllSchool();
        return response()->json(
            [
                "status" => 200,
                "sectors" => $sectors,
                "majors" => $majors,
                "areas" => $areas,
                "school_types" => $types,
                "schools" => $schools
            ],
            200
        );
    }

    public function getSchool($school_code){
        $school = $this->schoolRepo->getSchool($school_code);
        $majors = $this->majorRepo->getMajorInArray(json_decode($school->school_majors));
        $school->school_majors = $majors;
        if($school != null){
            return response()->json([
                "code" => 200,
                "school" => $school
            ],200);
        }else{
            return response()->json([
                "code" => 404,
                "message" => "Trường này không tồn tại!"
            ],404);
        }

    }

    public function getSector($sector_id)
    {
        $sector = $this->sectorRepo->getSector($sector_id);
        if($sector != null) {
            $schools = $this->schoolRepo->getSchoolBySectorId($sector_id);
            $sector->schools = $schools;
            return response()->json([
                "code" => 200,
                "sector" => $sector
            ], 200);
        }else{
            return response()->json([
                "code" => 404,
                "message" => "Nhóm ngành không tồn tại!"
            ],404);
        }
    }

    public function getPage($slug)
    {
        $page = $this->pageRepo->getPage($slug);
        if($page){
            return response()->json(
                [
                    "status" => 200,
                    "page" => $page,
                ],
                200
            );
        }else{
            return response()->json(
                [
                    "status" => 404,
                    "message" => "Trang không tồn tại",
                ],
                404
            );
        }
    }

    public function getAllComment($code)
    {
        return response()->json([
            "code" => 200,
            "comments" => $this->commentRepo->getCommentsBySchoolCode($code)
        ]);
    }
}
