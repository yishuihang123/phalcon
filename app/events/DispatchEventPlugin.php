<?php

namespace Events;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

/**
 * 事件调度监听行为事件类
 * 
 * @author yishuihang<437087977@qq.com>
 */
class DispatchEventPlugin extends \Phalcon\DI\Injectable implements \Phalcon\Events\EventsAwareInterface, \Phalcon\DI\InjectionAwareInterface{
    
    /**
     * 路由执行前监听
     * 
     * @param Event $event  事件
     * @param Dispatcher $dispatcher    事件服务
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        
    }
    
    /**
     * 
     * 在每一个找到的动作后执行
     * @param Event $event  事件
     * @param Dispatcher $dispatcher    事件服务
     */
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        
    }
    
    /**
     * 
     * 异常抛出前监听
     * @param Event $event  事件
     * @param Dispatcher $dispatcher    事件服务
     */
    public function beforeException(Event $event, Dispatcher $dispatcher)
    {
        
    }
}
