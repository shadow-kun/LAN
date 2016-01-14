<?php 
	$ip = $_SERVER['REMOTE_ADDR']; 
	echo shell_exec('arp -a ' . $ip);?>