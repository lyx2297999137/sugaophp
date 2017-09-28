<?php

namespace sugaophp\validate;

class ValidateString {

    public $error;
    public $name;
    public $value;

    /**
     * 验证字符串长度
     * @author ushe
     * @version 0.1
     * @param $min 最小长度
     * @param $max 最大长度
     * @return bool|int
     */
    public function validateLength($min = null, $max = null) {
        if (empty($min) && empty($max)) {
            $this->error = 'length验证必须有一个参数';
            return false;
        }
        $min = intval($min);
        $rule = is_null($max) ? $min : "{$min},{$max}";
        $result = preg_match("#^[\d\D\x{4e00}-\x{9fa5}]{{$rule}}$#iu", $this->value);
        if (empty($result)) {
            $this->error = $this->error . "($this->name)" . str_replace(',', '-', $rule) . "个字符";
        }
        return $result;
    }

    function __call($name, $arguments) {
       
    }

    public function __set($name, $value) {
        $this->$name=$value;
    }

}
