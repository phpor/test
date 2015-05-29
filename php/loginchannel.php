#!/usr/bin/env php
<?php
$mypwd = "123";
$arrIp = array("10.0.2.2");
while(true) {
	echo "please input password:";
	$pwd = trim(fgets(STDIN));
	$pwd = "123";
	if ($pwd != $mypwd) continue;
	$user = posix_getlogin();
	$user = 'vagrant';

	while(true) {
		echo "please input ip to connect[ q for exit ]:";
		$ip = trim(fgets(STDIN));
		$ip = "10.0.2.2";
		if ($ip == "q") exit;
		if (!in_array($ip, $arrIp)) {
			echo "forbidden\n";continue;
		}
	echo	passthru("ssh -i /Users/phpor/.vagrant.d/boxes/web/0/virtualbox/vagrant_private_key $user@$ip -p 2222");
	}
}
