# proxmox-zabbix
Simple script for proxmox-monitoring in Zabbix (tested on 5.4.7)
# Installation
- Copy PHP-files to `/etc/zabbix/scripts`
- Get access token in proxmox-web-panel
- Enter credentials in `/etc/zabbix/scripts/get.vm.php` (replace them in 'define' section)
- Add content from `zabbix_agentd.conf` to the end of your `zabbix_agentd.conf` file; restart zabbix-agent service
- Import new template to Zabbix, add it to your PM node

# Discovery
- `vmid`
- `vmname`

# Items prototype
- `status`
- `uptime`
- `cpu_usage`
- `free_memory`
- `total_memory`

# Triggers prototype
- VM is not running (status != 1)
- VM was restarted (uptime < 600)
- CPU Usage is too high (over 90%)

# Graphs prototype
- Availability (status == 1)
- CPU Usage
- Memory (free & total)
