<?php

namespace Library\Redis;


use Predis\Client;
/**
 * redis 客户端类型
 * 
 * @author yishuihang
 */
class RedisClient {
    
    
    
    /**
     * Redis客户端实例
     * @var type object 
     */
    private $redis;
    
    /**
     * 
     * @param type $redisConfigArr redis配置
     */
    public function __construct($redisConfigArr=array()){
       $type = isset($redisConfigArr['type']) ? trim($redisConfigArr['type']) : '';
        if($type=='replication'){   // 主从
            $parameters=$redisConfigArr['servers'];
            $options    = ['replication' => true];
            $this->redis=new Client($parameters, $options);
        }elseif($type=='cluster'){  // 集群
            $parameters=$redisConfigArr['servers'];
            $options    = ['cluster' => 'redis'];
            $this->redis=new Client($parameters, $options);
        }else{  // 单机
            $parameters=$redisConfigArr;
            $this->redis=new Client($parameters);
        }
    }
    
    public function __call($name, $params) {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $params);
        } elseif (method_exists($this->redis, $name)) {
            throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
        } else {
            // TODO 
            if(count($params)==1){
                return $this->redis->$name($params[0]);
            }elseif(count($params)==2){
                return $this->redis->$name($params[0],$params[1]);
            }elseif(count($params)==3){
                return $this->redis->$name($params[0],$params[1],$params[2]);
            }
        }
    }

}
