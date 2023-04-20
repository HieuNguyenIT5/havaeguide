<?php
namespace App\Repositories;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Repositories{

    private $url = "https://provinces.open-api.vn/api/p";

    public function getModelWithStatus($status, $model){
        if($status == 'hide'){
            $model = $model->onlyTrashed();
        }elseif($status==''){
            $model = $model->withTrashed();
        }
        return $model;
    }
    public function saveImage($request, $column, $imageOld = null){
        if ($request->hasFile($column)) {
            $image = $request->file($column);
            $fileName = $column.time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $fileName);
            //Xóa file ảnh cũ
            $imagePath = public_path("images") . '/' . $imageOld;
            if ($imageOld != null && file_exists($imagePath)) {
                unlink($imagePath);
            }
            return $fileName;
        }else{
            return $imageOld;
        }
    }

    public function getListStatus($status){
        if($status == 'hide'){
            $list_act = [
                'restore' => 'Hiển thị lại',
                'delete' => 'Xóa vĩnh viễn'
            ];
        }elseif($status=='active'){
            $list_act = [
                'remove' => 'Ẩn',
            ];
        }else{
            $list_act = [
                'restore' => 'Hiển thị lại',
                'remove' => 'Ẩn',
                'delete' => 'Xóa vĩnh viễn'
            ];
        }
        return $list_act;
    }

    public function getArea(){
        $areas = Redis::get("areas");
        if(is_null($areas)){
            $data = file_get_contents($this->url);
            $areas = json_decode($data, true);
            Redis::set("areas", $areas);
        }else{
            $areas = json_decode($areas);
        }
        return $areas;
    }
    public function getAreaCenter(){
        $areasCenter = Redis::get("areasCenter");
        if(is_null($areasCenter)){
            $data = file_get_contents($this->url);
            $areasCenter = json_decode($data, true);
            $areasCenter = array_filter($areasCenter, function($areasCenter) {
                return $areasCenter['division_type'] == 'thành phố trung ương' ||
                in_array($areasCenter['code'], [15, 8, 19]);
            });
            Redis::set("areasCenter", json_encode($areasCenter));
        }else{
            $areasCenter = json_decode($areasCenter);
        }
        $areasCenter = json_decode(Redis::get("areasCenter"));
        return $areasCenter;
    }
}



