<?php

namespace App\Repositories\School;

interface ISchoolRepository{
    public function getSchool($request, $status);
    public function createSchool();
    public function storeSchool($request);
    public function editSchool($id);
    public function updateSchool($request, $id);
    public function removeSchool($request);
    public function deleteSchool($request);
    public function count();
    public function total();
}