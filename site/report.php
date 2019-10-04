<?php
declare(strict_types = 1);

include __DIR__ . '/config.php';
$subdomain = require './subdomain.php';

$database = new PDO("mysql:host=$dbHost;dbname=$dbSchema", $dbUsername, $dbPassword);
$json = file_get_contents('php://input');
$reports = json_decode($json, true);

$key = array_key_first($reports);
if (is_int($key)) {
	$types = array_map(function (array $report): string {
		return $report['type'];
	}, $reports);
} else {
	$types = [$key];
}

if ($types && $reports) {
	$statement = $database->prepare('INSERT INTO reports (who, received, types, report) VALUES (?, NOW(), ?, ?)');
	$statement->execute([
		$subdomain,
		json_encode($types),
		$json
	]);
	echo 'OK';
} else {
	echo 'No type specified or invalid JSON';
}
