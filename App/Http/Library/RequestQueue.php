<?php
namespace App\Library;

use \Illuminate\Support\Facades\Redis;

class RequestQueue {

    private $expire = 2000;
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
        $this->queue = json_decode($this->redis->get($this->name));
        if(!is_array($this->queue)){
            $this->queue = [];
        }
    }

    private function set($value = ''){
        if($value == $this->name){
            return;
        }
        if($value != '') {
            array_push($this->queue, $value);
        }
        $this->queue = array_values($this->queue);
        $this->redis->set($this->name, json_encode($this->queue));
        $this->redis->pexpire($this->name, $this->expire);
    }

    private function waitOn($me){
        wait: {
            $this->get();
            if(is_array($this->queue) && sizeof($this->queue) > 0 && $this->queue[0] !== $me){
                usleep(400);
                goto wait;
            }
        }

        return true;
    }

    private function remove($keys = ''){
        $this->get();

        if(!is_array($this->queue)){
            return;
        }

        $key = -1;
        if($keys != '') {
            $key = array_search($keys, $this->queue);
        }

        if($keys != '' && $key < 0){
            return;
        }

        if($key > -1){
            unset($this->queue[$key]);
        }else {
            array_shift($this->queue);
        }

        $this->set();
    }

    private function kill(){
        if(is_null($this->redis)){
            return;
        }

        $this->redis->del($this->name);
    }
}
