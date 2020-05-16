<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

echo \Can\Has\pageHead('XSS Auditor');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>XSS Auditor reports</h1>
	<p><em>
		Designed to help stop <em>Reflected Cross-Site Scripting</em> attacks but often exploited to extract information from pages.
		Introduced in Internet Explorer 8 back in 2009 as <em>XSS Filter</em> and in Chrome 4 in 2010, controlled by the <code>X-XSS-Protection</code> HTTP header.
		The feature was completely <a href="https://www.chromestatus.com/feature/5021976655560704">removed in Chrome 78</a>
		and in <a href="https://blogs.windows.com/windowsexperience/2018/07/25/announcing-windows-10-insider-preview-build-17723-and-build-18204/">Microsoft Edge in 2018</a>.
		Chrome was the only browser with XSS Auditor reporting (enabled with the <code>report</code> field in the <code>X-XSS-Protection: 1; report=&lt;url&gt;</code> header).
	</em></p>
</div>
</body>
