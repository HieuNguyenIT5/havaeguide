<?php
namespace App\Repositories\Sector;

interface ISectorRepository{
    public function getSector($request, $status);
    public function storeSector($request);
    public function editSector($request);
    public function updateSector($request);
    public function removeSector($id);
    public function restoreSector($id);
    public function deleteSector($id);
    public function count();
    public function total();
}