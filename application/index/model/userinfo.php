<?php

namespace app\index\model;

use think\Model;

class userinfo extends Model
{
    // 添加
    public function creat($data){

       $id= $this->save($data);

       return $id;
    }



}
