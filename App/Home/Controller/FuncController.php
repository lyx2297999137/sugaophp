<?php
/**
 * 方法测试
 */
namespace App\Home\Controller;
//http://www.sugaophp12.com/?controller=func&action=uasort
//http://www.sugaophp12.com/?controller=func&action=dump
class FuncController {
    function index(){
       
    }
    /**
     * 多维数组排序
     */
    function uasort(){
        $tourism_area_list = [
            'quangang' => [
                'name' => '泉港',
                'num' => 0,
                'value' => 0
            ],
            'huian' => [
                'name' => '惠安',
                'num' => 0,
                'value' => 0
            ],
            'kaifaqu' => [
                'name' => '开发区',
                'num' => 0,
                'value' => 0
            ],
            'taishangqu' => [
                'name' => '台商区',
                'num' => 0,
                'value' => 0
            ],
            'luojiang' => [
                'name' =>'洛江',
                'num' => 0,
                'value' => 0
            ],
            'fengze' =>  [
                'name' =>'丰泽',
                'num' => 0,
                'value' => 0
            ],
            
        ];
            uasort($tourism_area_list, "uasort_func");
            dump1($tourism_area_list);
    }
}
