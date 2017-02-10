<?php

/**
 * 路由文件 
 * 
 */
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

$router = new Router();

$router->add('/', [ 'namespace' => 'Controllers', 'controller' => "Index", 'action' => 'index']);

// 返回路由实例
return $router;
