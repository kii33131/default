<?php

namespace app\index\model;

use think\Model;

class Userinfo extends Model
{

    public function address()
    {
        return $this->hasMany('address');
    }
    // 添加
    public function creat($data){

       $id= $this->save($data);

       return $id;
    }



}
