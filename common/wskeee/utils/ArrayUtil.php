<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wskeee\utils;

/**
 * Description of ArrayUtil
 *
 * @author Administrator
 */
class ArrayUtil {

    private static $hasInit = false;
    private static $sortNames = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '上', '中', '下', '全'];
    /**
     * 针对以下方案排序 <br/>
     * $arr = ['第一期','第二期','第三期','第二十一期','第三期','第四期','第五期','第十期','第九期','第七期'];<br/>
     * $arr0 = ['一年级','三年级','九年级','八年级','七年级'];<br/>
     * $arr1 = ['第1期','第2期','第3期','第5期','第6期','第4期','第10期','第11期','第15期','第20期'];<br/>
     * $arr2 = ['1','2','3','5','6','10','4'];<br/>
     * $arr3 = ['a','b','c','e','d','g','h'];<br/>
     * $arr4 = ['hello','car','banane','e','d','g','h'];<br/>
     * $arr5 = ['Unit 1','Unit 2','Unit 6','Unit 3'];<br/>
     * $arr6 = ['1','2','1-4','3'];<br/>
     * @param type $arr
     * @return type
     */
    public static function sortCN($arr) {
        
        if(!self::$hasInit){
            self::$hasInit = true;
            self::$sortNames = array_flip(self::$sortNames);
        }
        
        usort($arr, function ($a, $b) {
            if ($a == $b)
                return 0;
            //都为数字按字符排序
            if (is_numeric($a) && is_numeric($b)) {
                return (integer) $a < (integer) $b ? -1 : 1;
            }
            //分拆每一个字
            $a_arr = preg_split('/(?<!^)(?!$)/u', $a);
            $b_arr = preg_split('/(?<!^)(?!$)/u', $b);
            $a_value = 0;
            $b_value = 0;
            //如果存在于自定义的规则中即取对应值相加,最后比值大小
            foreach ($a_arr as $v) {
                $a_value += isset(self::$sortNames[$v]) ? self::$sortNames[$v] : 0;
            }
            foreach ($b_arr as $v) {
                $b_value += isset(self::$sortNames[$v]) ? self::$sortNames[$v] : 0;
            }
            //如果不符合自定义规则即使用默认字符排序
            $bo = ($a_value == 0 && $b_value == 0) ? $a < $b : $a_value < $b_value;
            return $bo ? -1 : 1;
        });
        return $arr;
    }

}
