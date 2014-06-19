<?php
$arropt = getopt('n:');
$n = isset($arropt['n'])?$arropt['n']:10;

$arr = array();
while($num = trim(fgets(STDIN))) {
        if(count($arr) < $n || $num > min($arr)) {
                $arr[] = $num;
                rsort($arr);
        }
        if (count($arr) > $n) array_pop($arr);
}
print_r($arr);
