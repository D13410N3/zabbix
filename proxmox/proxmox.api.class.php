<?php

class Proxmox_API {
	public 		$address;
	public		$port = 8006;
	public		$username;
	public		$token;
	public		$node;
		
	public function __construct($address, $port, $username, $token, $node) {
		$this -> address = $address;
		$this -> port = $port;
		$this -> username = $username;
		$this -> token = $token;
		$this -> node = $node;
		$this -> base_url = 'https://'.$this -> address.':'.$this -> port.'/api2/json';
	}
	
	public function make_request($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: PVEAPIToken='.$this -> username.'='.$this -> token));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		// curl_setopt($ch, CURLOPT_VERBOSE, 1);
		$a = curl_exec($ch);
		return $a;
	}
	
	public function get_list_vm() {
		$url = $this -> base_url.'/nodes/'.$this -> node.'/qemu';
		$a = $this -> make_request($url);
		/* Example output:
		    {
			  "netin": 214640808,
			  "diskwrite": 0,
			  "maxdisk": 68719476736,
			  "pid": 1794,
			  "disk": 0,
			  "name": "zabbix",
			  "netout": 223679237,
			  "cpu": 0.0245332916263377,
			  "diskread": 0,
			  "mem": 3017373309,
			  "maxmem": 12884901888,
			  "cpus": 8,
			  "uptime": 17766,
			  "status": "running",
			  "vmid": 100
			}
		*/
		$j = json_decode($a, true);
		$vms = array();
		foreach ($j['data'] as $vm)
			{
				// You can modify adding additional info
				$vms[$vm['vmid']] = $vm['name'];
			}
		
		return $vms;
	}
	
	public function get_vm_status($vmid) {
		$url = $this -> base_url.'/nodes/'.$this -> node.'/qemu/'.$vmid.'/status/current';
		$a = $this -> make_request($url);
		return json_decode($a, true);
	}

}
