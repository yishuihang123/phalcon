<?php

namespace Controllers;

use Phalcon\Di;

/**
 * Description of IndexController
 *
 * @author tp
 */
class IndexController extends BaseController {

    public function indexAction() {
        // test 1:路由调度控制器
//        return $this->responseJson('200', 'c', $this);exit;
        // test2：数据库连接操作
//        $sql = "SELECT * FROM member limit 2";
//        $result = $this->member->query($sql);
//        while ($data   = $result->fetch()) {
//            return $this->responseJson('200', 'c', $data);
//            exit;
//        }
        // test3：redis连接操作
//        // 单机
//        dump($this->redis->default->set('test070210','test0702asd'));
//        dump($this->redis->default->get('test070210'));
//        // 主从
//        // 集群
        // test4：服务
//        dump($this->service->demo);
//        dump($this->service->setService('demo',new \Services\DemoService()));
//        dump($this->service->getSharedService('demo'));
    }

}
