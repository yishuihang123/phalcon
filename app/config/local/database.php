<?php
/**
 * 本地环境配置文件：数据库连接
 * 
 */
return array(
    // 默认mysql数据配置
    'default'         => array(
        'host'     => '192.168.148.172',
        'dbname'   => 'member',
        'username' => 'test',
        'password' => '123456',
    ),
    'member'         => array(
        'host'     => '192.168.148.172',
        'dbname'   => 'member',
        'username' => 'test',
        'password' => '123456',
    ),
    'oracleDBConnect' => ['username' => '***', 'password' => '***', 'dbname' => '192.168.155.180:1521/***'], // oracle数据库配置
);
