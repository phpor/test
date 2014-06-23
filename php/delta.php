<?php
/**
 * 通过管道给该脚本如下格式的数字： 
 * xxx:123,yyy:345,zzz:456\r\n
 */
$firstline = true;
$arr_old = array();
while(!feof(STDIN)) {
        $line = trim(fgets(STDIN));
        if(!$line) continue;
        $arr = array();
        $_arr = explode(",", $line);
        foreach($_arr as $item) {
                list($k, $v) = explode(':', $item);
                $arr[$k] = $v;
        }
        if ($firstline) {
                foreach(array_keys($arr) as $k) {
                        printf("%012s", $k);
                }
                printf("\n");
                $firstline = false;
        }
        foreach($arr as $k=>$v) {
                if (isset($arr_old[$k])) $v = $v - $arr_old[$k];
                printf("%12d", $v);
        }
        $arr_old = $arr;
        printf("\n");
}
