<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

header('Expect-CT: max-age=0, report-uri="' . \Can\Has\reportUrl() . '"');
// header('Expect-CT: max-age=86400, enforce, report-uri="/report.php"');
echo \Can\Has\pageHead('Expect-CT');
?>
<body>
<div>
<?= \Can\Has\bookmarks('index', 'reports'); ?>
<code>Expect-CT</code> header, where <em>CT</em> stands for <em>Certificate Transparency</em>
</div>
</body>
