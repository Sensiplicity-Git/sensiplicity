<?php
include('lock.php');
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
        header("location: login.php");
  }


require("header3.php");
 
$UpdateServer = $_REQUEST["UpdateNetwork"];
$net_settings = isset($_REQUEST["NetworkSettings"]) ? $_REQUEST["NetworkSettings"] : "";
$ip_address = isset($_REQUEST["ip_address"]) ? $_REQUEST["ip_address"] : "";
$routers = isset($_REQUEST["routers"]) ? $_REQUEST["routers"] : "";
$domain_name_servers = isset($_REQUEST["domain_name_servers"]) ? $_REQUEST["domain_name_servers"] : "";

if ($net_settings == "static") {
	$output = shell_exec("head -44 /etc/dhcpcd.conf > /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo '' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'interface eth0' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'static ip_address=$ip_address' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'static routers=$routers' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'static domain_name_servers=$domain_name_servers' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo '' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'reboot 0' >> /tmp/dhcpcd.conf.new");
}
if ($net_settings == "dhcp") {
	$output = shell_exec("head -44 /etc/dhcpcd.conf > /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo '' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'allowinterfaces eth0' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo '' >> /tmp/dhcpcd.conf.new");
	$output = shell_exec("echo 'reboot 0' >> /tmp/dhcpcd.conf.new");
}

$output = shell_exec("/bin/cp /tmp/dhcpcd.conf.new /etc/dhcpcd.conf");

header("Location: manage_network.php");

?> 

