<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Redis extends Controller
{
    private $_config = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'index' => 0,
        'auth' => '',
        'timeout' => 1,
        'reserved' => NULL,
        'retry_interval' => 100,
    );
    private $_redis;

    /**
     * 初始化
     * @param Array $config redis连接设定
     */
    public function __construct(){
        // $this->_config = $config;
        $this->_redis = $this->connect();
    }


    /**
     * 获取锁
     * @param  String  $key    锁标识
     * @param  Int     $expire 锁过期时间
     * @return Boolean
     */
    public function lock($key, $expire=5){
        $is_lock = $this->_redis->setnx($key, time()+$expire);

        // 不能获取锁
        if(!$is_lock){

            // 判断锁是否过期
            $lock_time = $this->_redis->get($key);

            // 锁已过期，删除锁，重新获取
            if(time()>$lock_time){
                $this->unlock($key);
                $is_lock = $this->_redis->setnx($key, time()+$expire);
            }
        }

        return $is_lock? true : false;
    }

    /**
     * 释放锁
     * @param  String  $key 锁标识
     * @return Boolean
     */
    public function unlock($key){
        return $this->_redis->del($key);
    }

    /**
     * 创建redis连接
     * @return Link
     */
    private function connect(){
        try{
            $redis = new Redis();
            $redis->connect($this->_config['host'],$this->_config['port'],$this->_config['timeout'],$this->_config['reserved'],$this->_config['retry_interval']);
            if(empty($this->_config['auth'])){
                $redis->auth($this->_config['auth']);
            }
            $redis->select($this->_config['index']);
        }catch(RedisException $e){
            throw new Exception($e->getMessage());
            return false;
        }
        return $redis;
    }


    public function setnx($key,$value){


        return  $this->_redis->setnx($key,$value);
    }


    public function get($key){

        return  $this->_redis->get($key);
    }

    public function setEx($key,$time,$value){

        return  $this->_redis->setEx($key,$time,$value);
    }

    // 商品库存存redis
    public function set_stock($stock,$goods_id){

        $key = 'seckill'.'_'.$goods_id;

        for ($i=0;$i<$stock;$i++){

            $this->_redis->lpush($key,1);
        }

    }

    //秒杀出库存
    public function out_stock($goods_id){

        $key = 'seckill'.'_'.$goods_id;
        $count = $this->_redis->lpop($key);
        if(!$count){
            //debug_log_write();
            return false;
        }

        return true;
    }


    public function llen($key){

        return   $this->_redis->llen($key);
    }


    public function set($key,$val){

        $bool=$this->_redis->set($key,$val);
        return $bool;
    }
}
