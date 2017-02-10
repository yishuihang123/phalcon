<?php

/**
 * 本地环境差异配置文件
 * 
 */
return array(
    'database' => require 'local/database.php',
    'redis'    => require 'local/redis.php',
    'api'      => require 'local/api.php',
);
