<?php

namespace Services;

use Phalcon\Di\Service;

/**
 * 服务基类
 * 
 * @author yishuihang<437087977@qq.com>
 */
class BaseService {

    /**
     * 
     * @var type 
     */
    protected static $_service=array();

    /**
     * 注入服务类
     * 
     * @param type $name
     */
    function setService($name,$object) {
        if(array_key_exists($name, self::$_service)){
           return true; 
        }
        self::$_service[$name]=$object;
    }

    /**
     * 获取服务类
     * 
     * @param type $name
     */
    function getService($name) {
        $className="\Services\\".ucfirst($name).'Service';
        $serviceObj=new $className();
        return $serviceObj;
    }
    
    /**
     * 获取共享服务
     * 
     * @param type $name
     */
    function getSharedService($name) {
        if (array_key_exists($name, self::$_service)) {
            return self::$_service[$name];
        }
        return false;
    }
    
    /**
     * 
     * @param type $name
     * @return type
     */
    function __get($name) {
        
        return $this->getService($name);
    }

}
