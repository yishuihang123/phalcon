<?php
/**
 * 程序入口文件
 * 
 * @author 
 */
use Phalcon\Config;
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Library\Log\Log;
use Events\DispatchEventPlugin;

// 定义常量
define('DEBUG', TRUE);
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', realpath('..') . '/');                                                           // 项目目录路径
define('APP_LOG_PATH', APP_PATH . 'storages' . DS . 'logs' . DS);                                   // 日志目录路径
define('APP_VIEW_PATH', APP_PATH . 'app' . DS . 'views' . DS);                                      // 视图目录路径
define('APP_VIEW_COMPILE_PATH', APP_PATH . 'storages' . DS . 'compile' . DS);                       // 视图编译目录路径
define('APP_CONFIG_PATH', APP_PATH . 'app' . DS . 'config' . DS);                                   // 配置目录路径
define('APP_EXCEPTION', 'application.exception');                                                   // 
define('URL_PATH', dirname('http://' . $_SERVER['SERVER_NAME']) . '/');                             // 获取当前URL
require_once(APP_CONFIG_PATH.'const.php');   

// 加载配置文件和公共函数文件
$configFile = APP_CONFIG_PATH . 'config.php';
$settings = require_once($configFile);   
$config = new Config($settings);
$helpers=require_once(APP_PATH. 'app' . DS .'helpers.php');
$envConfigFile = APP_CONFIG_PATH . 'config_' . $config->environment . '.php';
if (is_readable($envConfigFile)) {
    $envSettings = require_once($envConfigFile);
    $envConfig = new Config($envSettings);
    $config->merge($envConfig);
}
date_default_timezone_set($config->timezone);

require APP_PATH . 'vendor/autoload.php';

if (DEBUG || $config->environment == 'local') {
   error_reporting(E_ALL);
   ini_set('display_errors', '1');
} elseif ($config->environment == 'test') {
   error_reporting(E_ALL);
   ini_set('display_errors', '1');
} elseif ($config->environment == 'pre_release') {
   error_reporting(0); 
   ini_set('display_errors', '0');
} elseif ($config->environment == 'production') {
   error_reporting(0); 
   ini_set('display_errors', '0');
}   

function catchException($exception)
{
    $_GET['_url']=array_key_exists('_url', $_GET) ? $_GET['_url'] : '/' ;
    $data = array(
        'code' => 5000,
        'message' => "服务器数据异常",
        'detail' => array(
            'exCode'   => $exception->getCode(),
            'url'      =>   $_GET['_url'],
        ),
    );
    $logDetail  = "Url:" . $_GET['_url'] . "<br />\r\n";
    $logDetail .= "Message:" .$exception->getMessage() . "<br />\r\n";
    $logDetail .= "Trace:" . $exception->getTraceAsString();
    Log::error($logDetail, APP_EXCEPTION);var_dump($logDetail); dump($logDetail);
    echo json_encode($data);
}

function catchError($errno, $errstr, $errfile, $errline)
{
    $_GET['_url']=array_key_exists('_url', $_GET) ? $_GET['_url'] : '/' ;
    $logDetail  = "Url:" . ( array_key_exists('_url', $_GET) ? $_GET['_url'] : '/' ) . "<br />\r\n";
    $logDetail  = "Errno:" . $errno . "<br />\r\n";
    $logDetail .= "Errfile:" .$errfile . "<br />\r\n";
    $logDetail .= "Errline:" .$errline. "<br />\r\n";
    $logDetail .= "Errstr:" . $errstr;
    Log::error($logDetail, APP_EXCEPTION);
    $data = array(
        'code' => 5001,
        'message' => "服务器数据异常",
        'detail' => array(
            'exCode'   => $errno,
            'url'      => ( array_key_exists('_url', $_GET) ? $_GET['_url'] : '/' ),
        ),
    );
    dump($logDetail);
    if ($errno !== E_WARNING &&  $errno !== E_NOTICE && $errno !== E_COMPILE_WARNING && $errno !== E_USER_WARNING &&
        $errno !== E_USER_NOTICE && $errno !== E_DEPRECATED && $errno !== E_USER_DEPRECATED) {
        echo json_encode($data);
        exit();
    }
    if (DEBUG) {
        echo json_encode($data);
        exit();
    }
}

set_exception_handler('catchException');
set_error_handler('catchError');


// Register an autoloader
$loader = new Loader();
$loader->registerDirs(array(
    APP_PATH . 'app/controllers/',  // 控制器目录
    APP_PATH . 'app/models/',       // 模型目录
    APP_PATH . 'app/services/',     // 服务目录（服务类目录，服务类都得注入命名空间）
    APP_PATH . 'app/library/',      // 第三方类目录（作为引入第三方类服务类目录，服务类都得注入命名空间）
    APP_PATH . 'app/events/',       // 事件目录
))->register();
$loader->registerNamespaces([
    "Controllers"                           => APP_PATH . 'app' . DS . 'controllers',
    'Models'                                => APP_PATH . 'app' . DS . 'models',
    'Services'                              => APP_PATH . 'app' . DS . 'services',
    'Services\\Demo'                        => APP_PATH . 'app' . DS . 'services'.DS . 'demo',
    'Library'                               => APP_PATH . 'app' . DS . 'library',
    'Library\\Log'                          => APP_PATH . 'app' . DS . 'library'.DS . 'log',
    'Library\\Redis'                        => APP_PATH . 'app' . DS . 'library'.DS . 'redis',
    'Events'                                => APP_PATH . 'app' . DS . 'events',
])->register();

// 创建容器
$di = new FactoryDefault();

// 注入配置文件类
$di->set('config', $config);

$di->set('voltService', function ($view, $di) {
    $volt = new Volt($view, $di);
    $volt->setOptions(
        array(
            "compileAlways"     => true,
            "compiledPath"      => function ($templatePath) {
                    $templateVirtualDir = dirname(str_replace(APP_VIEW_PATH, '', $templatePath));
                    $templateName = basename($templatePath);
                    $compilePath = APP_VIEW_COMPILE_PATH . $templateVirtualDir . DS;
                if (!file_exists($compilePath)) {
                    mkdir($compilePath, 0777, true);
                }
                    return $compilePath . $templateName .  '.compiled';
            }
        )
    );
     return $volt;
});

// 注入视图
$di->set('view', function () {
    $view = new View();
    $view->setViewsDir(APP_VIEW_PATH);
    $view->registerEngines(array(
        ".phtml" => 'voltService'
    ));
    return $view;
});

// 注入url管理服务
$di->set('url', function () {
    $url = new Url();
    $url->setBaseUri('/');
    return $url;
});

// 注入路由
$routeFile = APP_PATH . 'app/routes.php';
if (file_exists($routeFile)) {
    $di->set('router', function () use ($routeFile) {
        return require_once $routeFile;
    });
}
  
// 注入服务类
$di->set('service', function () {
    return new Services\BaseService();
});

// 注入缓存服务
$di->set('redis', function () use ($config) {
    return  new Library\Redis\Redis();
});

// 注入数据库服务
foreach ($config->database as $conn => $options) {
    if ($conn == 'default') {   // 默认mysql引擎数据库，默认数据名称为default，使用方式为:$this->db->query($sql)
        $di->set('db', function () use ($options) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql($options->toArray());
        });
    } elseif ($conn == 'oracleDBConnect') { // oracle引擎数据库，使用方式为:$this->oracle_oracleDBConnect->...
        $connName = 'oracle_' . $conn;
        $di->set($connName, function () use ($options) {
            return \Phalcon\Db\Adapter\Pdo\Oracle($options->toArray());
        });
    } else {    // 其他mysql数据库连接名称，使用方式为:$this->mysql_*->query($sql)
        $connName = 'mysql_' . $conn;
        $di->set($conn, function () use ($options) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql($options->toArray());
        });
    }
}

// 注入事件管理服务
$di->set('dispatcher', function () {
    $eventsManager = new EventsManager();
    // 侦听服务插件
    $eventsManager->attach('dispatch', new DispatchEventPlugin);
    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});


// 启动应用
$application = new Application($di);
echo $application->handle()->getContent();
