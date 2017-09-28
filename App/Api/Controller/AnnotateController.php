<?php

namespace App\Api\Controller;

//http://www.sugaophp12.com/?module=api&controller=annotate&action=index
class AnnotateController {

    /**
     * 第一个API测试
     * @param $testName string.length(6,10) 测试名称 mockPHP
     * @return string.success {code:1, data:[], message:测试成功}
     * @return string.error {code:0, data:[], message:测试失败}
     */
//    http://www.sugaophp12.com/?module=api&controller=annotate&action=index&username=123456
    function index($username = '') {
        $reflection = new \ReflectionMethod('\\App\\Api\\Controller\\AnnotateController', 'index');
        $comment = $reflection->getDocComment();   //全部注释
        $params = $reflection->getParameters();   //参数
        //注释转数组
        $comment = trim($comment, "/* \t\n\r\0\x0B");
        preg_match_all("|@(\w+) (.*)|i", $comment, $matches);
        foreach ($matches[0] as $key => $value) {
            $matches[0][$key] = trim($value);
            $matches[2][$key] = trim($matches[2][$key]);
        }

        //提取参数
        $return = [];
        foreach ($matches[2] as $key => $param) {
            if ($matches[1][$key] == "param") {
                $startpos = strpos($param, '(');
                $endpos = strpos($param, ')');
                $params_str = substr($param, $startpos + 1, $endpos - $startpos - 1);
                $params_arr = explode(',', $params_str);
            } else {
                if (strpos($param, 'success')) {
                    $return['success'] = substr($param, strpos($param, '{'));
                }
                if (strpos($param, 'error')) {
                    $return['error'] = substr($param, strpos($param, '{'));
                }
            }
        }

        $validateString = '\\sugaophp\\validate\\ValidateString';
        $validateClass = new $validateString();
        foreach ($params as $param) {
            $validateClass->name = $param->name;
        }
        $validateClass->value = $_GET[$validateClass->name];
        $validateResult = call_user_func_array([$validateClass, 'validateLength'], $params_arr);
        if ($validateResult) {  //成功
            if (isset($return['success'])) {
                echo $return['success'];
            } else {
                echo $return['error'];
            }
        } else { //失败
            echo json_encode(['code' => 400, 'message' => $validateClass->error]);
        }
    }

}
