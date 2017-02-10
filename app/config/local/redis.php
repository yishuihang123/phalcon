<?php
/**
 * 本地环境配置文件：redis连接
 * 
 */
return array(
    // 默认redis单机配置
    'default' => array(
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 1
    ),
    // redis集群配置
    'cluster'=>array(
        'servers' => [
            'tcp://192.168.158.71:6380?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.71:6381?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.71:6382?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.71:6383?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.71:6384?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.71:6385?password=ZtpG~3uJ7EZs',
            'tcp://192.168.158.72:6380?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.72:6381?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.72:6382?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.72:6383?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.72:6384?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.72:6385?password=ZtpG~3uJ7EZs',
            'tcp://192.168.158.73:6380?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.73:6381?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.73:6382?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.73:6383?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.73:6384?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.73:6385?password=ZtpG~3uJ7EZs',
            'tcp://192.168.158.74:6380?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.74:6381?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.74:6382?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.74:6383?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.74:6384?password=ZtpG~3uJ7EZs', 'tcp://192.168.158.74:6385?password=ZtpG~3uJ7EZs',
        ],
        'type'    => 'cluster'
    ),
    // redis 主从配置
    'replication'=>array(
        'servers' => [ 
            'tcp://10.0.0.1?alias=master', 'tcp://10.0.0.2?alias=slave-01'
        ],
        'type'    => 'replication'
    ),
);
