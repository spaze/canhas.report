<?php
declare(strict_types = 1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$database = new PDO("mysql:host=$dbHost;dbname=$dbSchema", $dbUsername, $dbPassword);
$json = file_get_contents('php://input');
$reports = json_decode($json, true);

header('Access-Control-Allow-Origin: *');
if (strtolower($_SERVER['REQUEST_METHOD']) === 'options') {
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Headers: Content-Type');
	http_response_code(200);
	echo 'YUP';
	exit;
}

if (!is_array($reports)) {
	\Can\Has\redirectToBase();
}

$key = array_key_first($reports);
if (is_int($key)) {
	$types = array_map(function (array $report): string {
		return $report['type'];
	}, $reports);
} else {
	$types = [$key];
}

$who = \Can\Has\who();
if ($who === null) {
	\Can\Has\redirectToBase();
}

if ($types && $reports) {
	$statement = $database->prepare('INSERT INTO reports (who, received, types, report) VALUES (?, NOW(), ?, ?)');
	$statement->execute([
		$who,
		json_encode($types),
		$json
	]);
	echo 'OK';
} else {
	echo 'No type specified or invalid JSON';
}
