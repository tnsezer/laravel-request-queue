<?php
namespace App\Library;
use \Illuminate\Support\Facades\Redis;

class RequestQueue {

    private $expire = 5;
    private $queue = [];
    private $redis = null;
    private $name;

    private function connect($name)
    {
        $this->redis = Redis::connection();
        $this->name = $name;
        $this->get();
    }

    private function get()
    {
        $this->queue = $this->redis->lrange($this->name, 0, 50);
    }

    private function set($value = '', $del = ''){
        if($value != '' && $value == $this->name){
            return;
        }

        //$this->get();
        if($del == '') {
            \Log::info('RD_SET : '. $this->name. " : " .$value);
            $this->redis->rpush($this->name, $value);
        }else{
            \Log::info('RD_DEL : '. $this->name. " : " .$value);
            $this->redis->lrem($this->name, 1, $value);
        }
        $this->redis->expire($this->name, $this->expire);
        //$this->get();
        //\Log::info('RD_QUE : '.json_encode($this->queue));
    }

    private function waitOn($me){
        wait: {
            $this->get();
            if(count($this->queue) > 1 && $this->queue[0] !== $me){
                usleep(100);
                goto wait;
            }
        }

        return true;
    }

    private function remove($name){
        $this->set($name, 1);
    }

    private function kill(){
        if(is_null($this->redis)){
            return;
        }

        $this->redis->del($this->name);
    }
}
