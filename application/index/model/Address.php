<?php

namespace app\index\model;

use think\Model;

class Address extends Model
{
    //

    public function userinfo()
    {
        return $this->belongsTo('userinfo');
    }
}
