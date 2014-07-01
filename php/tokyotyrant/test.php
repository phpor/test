<?php

$arropt = getopt('k:v:h:p:n:');
$host = $arropt['h'];
$port = $arropt['p'];
$tt = new STokyoTyrant();
$tt->connect($host, $port);
$type = $arropt['t'];
$result = '';
switch($type) {
	case 'put':
		$result = $tt->put($arropt['k'], $arropt['v']);
		break;
	case 'out':
		$result = $tt->out($arropt['k']);
		break;
	case 'get':
		$result = $tt->get($arropt['k']);
		break;
	case 'stat':
		$result = $tt->stat();
		break;
	case 'list':
		$tt->list_init();
		$num = $arropt['n']?:0;
		$i = $num;
		do {
			$result = $tt->list_next();
			echo "$result\n";
			if ($num && $i-- == 0) break;
		}while($result !== false);
		break;
	case 'traverse':
		$num = $arropt['n']?:0;
		$tt->traverse(function($key) use($tt){
			$val = $tt->get($key);
			echo $key,"\t$val\n";
		}, $num);
		break;
	default:
		echo 'Usage:';
}
echo $result;
echo "\n";
