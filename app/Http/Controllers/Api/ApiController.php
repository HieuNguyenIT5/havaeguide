<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Sector\ISectorRepository;
use App\Repositories\School\ISchoolRepository;
use App\Repositories\Major\IMajorRepository;
use App\Repositories\SchoolType\ISchoolTypeRepository;
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
    private $Repo;

    public function __construct(ISchoolRepository $schoolRepo, ISectorRepository $sectorRepo, SliderRepository $sliderRepo, Repositories $repo, IMajorRepository $majorRepo, ISchoolTypeRepository $typeRepo)
    {
        $this->sliderRepo = $sliderRepo;
        $this->sectorRepo = $sectorRepo;
        $this->schoolRepo = $schoolRepo;
        $this->majorRepo = $majorRepo;
        $this->typeRepo = $typeRepo;

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

    public function getSchoolFilter($id = 0)
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
                "schools"=>$schools
            ],
            200
        );
    }
}
