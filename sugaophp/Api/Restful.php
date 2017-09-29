<?php

namespace sugaophp\Api;

use Exception;

class Restful {

    //请求方法
    private $_requestMethod;
    //请求的资源名称
    private $_resourceName;
    //请求的资源ID
    private $_id;
    //允许请求的资源列表
    private $_allowResources = ['users', 'restful'];
    //允许请求的HTTP方法
    private $_allowRequestMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
    //常用状态码
    private $_statusCodes = [
        200 => 'OK',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Server Internal Error'
    ];

    //改成patch方法 ，url pathinfo模式才不会报错
    public function run() {
        try {
            $this->_setupRequestMethod();
            $this->_setupResource();
            if($this->_resourceName=='restful'){
                $this->_userHandle();
//                $this->_json(['message'=>'用户处理'.$this->_id]);
            }else{
                $this->_json(['message'=>'其他处理']);
            }
        } catch (Exception $e) {
//            $this->_json(['message'=>$e->getMessage(),'code'=>$e->getCode()]);
            $this->_json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * 初始化请求方法
     */
    private function _setupRequestMethod() {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        if (!in_array($this->_requestMethod, $this->_allowRequestMethods)) {
            throw new Exception('请求方法不被允许', 405);
        }
    }

    /**
     * 初始化请求资源
     */
    private function _setupResource() {
//        $path = $_SERVER['PATH_INFO'];
        $path = $_SERVER['REQUEST_URI'];
        $arr = explode('/', $path);
        $this->_resourceName = $arr[1];
        if (!in_array($this->_resourceName, $this->_allowResources)) {
            throw new Exception('请求资源不被允许', 405);
        }
        if (!empty($arr[3])) {
            $this->_id = $arr[3];
        }
    }

    /**
     * 初始化资源标识符
     */
    private function _setupId() {
        
    }

    /**
     * 输出json
     * @param type $array
     */
    private function _json($array, $code = 0) {
        if ($code > 0 && $code != 200 && $code != 204) {
            header("HTTP/1.1 " . $code . " " . $this->_statusCodes[$code]);
        }
        header('Content-Type:application/json;charset=utf-8');
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    //用户数据处理
    private function _userHandle(){
        $bodyParams=$this->_getBodyParams();
        dump($bodyParams);
    }
    /**获取请求参数
     * form-data无效啊。row有效{"user":1}
     * @return type
     * @throws Exception
     */
    private function _getBodyParams(){
        $row=file_get_contents('php://input');
        if(empty($row)){
            throw new Exception('请求参数错误',400);
        }
        return json_decode($row,true);
    }
}
