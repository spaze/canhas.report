<?php
$subdomain = $_SERVER['CAN_HAS_SUBDOMAIN'];
if ($subdomain === '') {
	$subdomains = require './subdomains.php';
	$subdomain = $subdomains[array_rand($subdomains)];
	header("Location: https://{$subdomain}.{$_SERVER['SERVER_NAME']}");
	exit;
}
return $subdomain;
