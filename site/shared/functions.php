<?php
declare(strict_types = 1);

namespace Can\Has;

function bookmarks(string ...$links): string
{
	$hrefs = [];
	foreach ($links as $link) {
		switch ($link) {
			case 'index':
				$hrefs[] = '<a href="' . htmlspecialchars(baseOrigin()) . '/">↩ Back</a>';
				break;
			case 'reports':
				$hrefs[] = '<a href="' . htmlspecialchars(reportOrigin()) . '/">Reports</a>';
				break;
		}
	} 
	return '<div id="bookmarks"><div>' . implode(' ', $hrefs) . '</div></div>';
}


function pageHead(?string $title = null): string
{
	return '<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>' . ($title ? " {$title} | " : '') . 'Reporting API Demos</title>
		<link rel="stylesheet" href="' . htmlspecialchars(baseOrigin()) . '/assets/style.css">
		<script src="' . htmlspecialchars(baseOrigin()) . '/assets/scripts.js"></script>
		<script src="' . htmlspecialchars(baseOrigin()) . '/assets/highlight.pack.js"></script>
		<script src="' . htmlspecialchars(baseOrigin()) . '/assets/highlight-init.js"></script>
		</head>';
}


function redirectToBase(): void
{
	header('Location: ' . baseOrigin() . '/');
	exit;
}


function cookieName(): string
{
	return 'who';
}


function cookie(): string
{
	$name = cookieName();
	$who = $_COOKIE[$name] ?? null;
	if ($who === null || preg_match('/^[a-z0-9-]+$/', $who) !== 1) {
		$_COOKIE[$name] = $who = randomSubdomain();
		\setcookie($name, $who, [
			'expires' => strtotime('1 year'),
			'secure' => true,
		]);
	}
	return $who;
}


function who(): ?string
{
	return $_SERVER['HAS_SUBDOMAIN'] ?: null;
}


function baseOrigin(): string
{
	return "https://{$_SERVER['CAN_HAS_BASE']}";
}


function baseSubdomainOrigin(string $subdomain): string
{
	return "https://{$subdomain}.{$_SERVER['CAN_HAS_BASE']}";
}


function reportOrigin(?string $who = null): string
{
	return 'https://' . ($who ?? cookie()) . ".{$_SERVER['HAS_BASE']}";
}


function reportUrl(): string
{
	return reportOrigin() . '/report.php';
}


function jsonReportHtml(array $data): string
{
	return htmlspecialchars(
		preg_replace(
			'/^(  +?)\\1(?=[^ ])/m',
			'$1',
			rawurldecode(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
		)
	);
}


function reports(\PDOStatement $statement): string
{
	$result = [];
	foreach ($statement as $row) {
		$counts = array_count_values(json_decode($row['types']));
		$types = [];
		foreach ($counts as $type => $count) {
			$types[] = "{$count}× $type";
		}
		$reports = json_decode($row['report'], true);
		$who = (isset($row['who']) ? htmlspecialchars($row['who']) : null);
		$result[] = sprintf('<p>%s <small>%s</small> <strong>%s</strong>%s%s</p><pre><code class="json">%s</code></pre>',
			htmlspecialchars($row['received']),
			htmlspecialchars(date_default_timezone_get()),
			htmlspecialchars(implode(' + ', $types)),
			(isset($reports[0]) && is_array($reports[0]) ? ' via Reporting API' : ''),
			(isset($who) ? ' from <code><a href="' . reportOrigin($who) . '"><strong>' . $who . '</strong></a></code>' : ''),
			jsonReportHtml($reports)
		);
	}

	if ($statement->rowCount() === 0) {
		return '<p>No reports yet</p>';
	} else {
		return implode("\n", $result);
	}
}


function maxAge(): int
{
	return 30 * 60;
}


function reportToHeader(): string
{
	$reportTo = [
		'group' => 'default',
		'max_age' => maxAge(),
		'endpoints' => [
			[
				'url' => reportUrl(),
			]
		],
		'include_subdomains' => true,
	];
	return 'Report-To: ' . json_encode($reportTo, JSON_UNESCAPED_SLASHES);
}


function reportToHeaderHtml(string $header, string $groupDescriptionHtml): string
{
	return '<h2>The <code>Report-To</code> response header:</h2>
		<pre><code class="json">' . htmlspecialchars($header) . '</code></pre>
		<ul>
			<li><code>group</code>: the name of the group, ' . $groupDescriptionHtml .  '</li>
			<li><code>max_age</code>: how long the browser should use the endpoint and report errors to it</li>
			<li>
				<code>endpoints</code>: reporting endpoint configuration, can specify multiple endpoints but reports will be sent to just one of them
				<ul>
					<li><code>url</code>: where to send reports to, must be <code>https://</code>, otherwise the endpoint will be ignored</li>
				</ul>
			</li>
		</ul>';
}


function nelHeader(): string
{
	$nel = [
		'report_to' => 'default',
		'max_age' => maxAge(),
		'include_subdomains' => true,
//	'success_fraction' => 0.5,  // 0.0-1.0, optional, no success reports if not present
//	'failure_fraction' => 0.5,  // 0.0-1.0, optional, all failure reports if not present
	];
	return 'NEL: ' . json_encode($nel, JSON_UNESCAPED_SLASHES);
}


function willTriggerReportToHtml(string $what = 'violation'): string
{
	return "Will trigger a report that will be sent asynchronously ({$what} visible in Developer Tools in the <em>Console</em> tab, you won't see the report in <em>Network</em> tab but you can still"
		. ' <a href="https://www.michalspacek.com/chrome-err_spdy_protocol_error-and-an-invalid-http-header#chrome-71-and-newer">view the reporting requests</a>)';
}


function checkReportsReportToHtml(): string
{
	return 'Check your <a href="' . htmlspecialchars(reportOrigin()) . '/">reports</a> (can take some time before the browser sends the report)';
}


function reportingApiNotSupportedHtml(string $messageSuffix = 'reporting will not work'): string
{
	return '<div class="reporting-api not-supported hidden">'
		. '😥 Your browser <a href="https://developer.mozilla.org/en-US/docs/Web/API/Reporting_API#Browser_compatibility">does not support</a> Reporting API, '
		. $messageSuffix
		. '</div>';
}


function scriptSourceHtmlStart(string $class, string $language = 'html'): bool
{
	static $counter = 0;

	return \ob_start(function (string $source) use ($class, $language, &$counter): string {
		// Remove the "global" indentation
		preg_match('/^(\t*)/', $source, $matches);
		$source = preg_replace("/^{$matches[1]}/m", '', $source);
		// Convert tabs to spaces
		do {
			$source = preg_replace("/^( *){$matches[1][0]}/m", '$1  ', $source, -1, $count);
		} while ($count > 0);

		return $source . '<p><a href="#source' . ++$counter . '" class="view-source ' . htmlspecialchars($class) . '" data-text-hide="hide the code" data-text-show="show the code" data-arrow-hide="▲" data-arrow-show="▼"><span class="text">show the code</span> <span class="arrow">▼</span></a></p>
			<pre id="source' . $counter . '" hidden><code class="' . htmlspecialchars($language) . '">' . htmlspecialchars($source) . '</code></pre>';
	});
}


function scriptSourceHtmlEnd(): bool
{
	return ob_end_flush();
}


function randomSubdomain(): string
{
	$subdomains = require __DIR__ . '/subdomains.php';
	return $subdomains[array_rand($subdomains)];
}
