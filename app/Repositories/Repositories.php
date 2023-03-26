<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repositories{
    public function getModelWithStatus($status, $model){
        if($status == 'hide'){
            $model = $model->onlyTrashed();
        }elseif($status==''){
            $model = $model->withTrashed();
        }
        return $model;
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
}