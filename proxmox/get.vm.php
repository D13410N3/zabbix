<?php

define('ADDRESS', '10.100.2.1');
define('PORT', '8006');
define('USERNAME', 'root@pam!monitoring');
define('TOKEN', 'token');
define('NODE', 'pm');

if(php_sapi_name() != 'cli') die('Cli only running');

if($argc < 2) {
	echo 'Usage:'.PHP_EOL;
	echo $argv[0].' list - get list of current VM (zabbix-discovery format)'.PHP_EOL;
	echo $argv[0].' 100 - show all information about vmid = 100'.PHP_EOL;
	echo $argv[0].' 100 <status|uptime|cpu_usage|free_memory|total_memory>'.PHP_EOL;
}

require_once 'proxmox.api.class.php';

$pm = new Proxmox_API(ADDRESS, PORT, USERNAME, TOKEN, NODE);

if ($argc == 2) {
	if ($argv[1] == 'list') {
		$list = $pm -> get_list_vm();
		$result = array();
		foreach ($list as $vmid => $vmname) {
			$result['data'][] = array('{#VMID}' => $vmid, '{#VMNAME}' => $vmname);
		}
		
		$json = json_encode($result);
		
		echo $json;
		exit;
	}

	if (intval($argv[1]) >= 100) {		
		$info = $pm -> get_vm_status($argv[1]);
		
		var_dump($info);
	}
} elseif($argc == 3) {
	if (intval($argv[1]) >= 100) {
		$info = $pm -> get_vm_status($argv[1]);
		
		if ($argv[2] == 'status') {
			$value = $info['data']['status'];
			$value = str_replace(array('running', 'stopped'), array(1, 0), $value);
		} elseif ($argv[2] == 'uptime') {
			$value = $info['data']['uptime'];
		} elseif ($argv[2] == 'cpu_usage') {
			$value = round($info['data']['cpu'], 4) * 100;
		} elseif ($argv[2] == 'free_memory') {
			$value = $info['data']['freemem'];
		} elseif ($argv[2] == 'total_memory') {
			$value = $info['data']['ballooninfo']['total_mem'];
		} else {
			die('unknown metric');
		}
		
		echo $value;
		
	}
}
