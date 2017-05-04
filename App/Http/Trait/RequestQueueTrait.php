<?php
namespace App\Http\Traits;

use \Illuminate\Support\Facades\Redis;

trait RequestQueueTrait {

    private $expire = 5;
    private $queue = [];
    private $redis = null;
    private $name;

    private function connect($name)
    {
        usleep(rand(1,200) * 10);
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

    private function set($value){
        if($value == $this->name){
            return;
        }

        array_push($this->queue, $value);
        $this->queue = array_values($this->queue);
        $this->redis->set($this->name, json_encode($this->queue));
        $this->redis->expire($this->name, $this->expire);
    }

    private function waitOn(){
        wait: {
            $this->get();
            if(is_array($this->queue) && sizeof($this->queue) > 0 && $this->queue[0] !== $this->name){
                usleep(10);
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

        $this->set(json_encode($this->queue));
    }

    private function kill(){
        if(is_null($this->redis)){
            return;
        }

        $this->redis->del($this->name);
    }
}