<?php

namespace Controllers;

/**
 * 控制器基类
 * 
 * @author 
 */
class BaseController extends \Phalcon\Mvc\Controller {

    public function initialize() {
        
    }

    public function render($controllerName, $actionName, $params = null) {
        
    }

    protected function responseJson($code, $message = '', array $ext = []) {
        $responseArr = [
            'code'      => $code,
            'message'   => $message,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        foreach ($ext as $k => $v) {
            $responseArr[$k] = $v;
        }
        $this->response->setHeader('Content-Type', 'application/json');
        return $this->response->setJsonContent($responseArr);
    }

    protected function responseSuccessJson($code, $message = '', array $ext = []) {
        
    }

    protected function responseErrorJson($code, $message = '', array $ext = []) {
        
    }

    public function redirect($message, $redirectUrl) {
        
    }

    public function getHeader() {
        return $this->request->getHeader();
    }

}
