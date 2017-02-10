<?php

namespace Library\Redis;

use Phalcon\Di;
use Library\Redis\RedisClient;

/**
 * 缓存服务客户端
 * 
 * @author 
 */
class Redis {
    
    /**
     * redis实例数组
     * 
     * @var type 
     */
    public static $_instances = array();
    
    /**
     * 获取redis客户端
     * 
     * @param type $name
     */
    function __get($name) {
        if(empty(self::$_instances[$name])){
            $redisConfigArrs=Di::getDefault()->getConfig()->redis->toArray();
            $redisConfigArr=in_array($name,$redisConfigArrs)?$redisConfigArrs[$name]:$redisConfigArrs['default'];// 默认取redis默认配置
            self::$_instances[$name]=new RedisClient($redisConfigArr);
        }
        return self::$_instances[$name];
    }

    

}
