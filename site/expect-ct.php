<?php
declare(strict_types = 1);

require __DIR__ . '/functions.php';

header('Expect-CT: max-age=0, report-uri="' . \Can\Has\reportUrl() . '"');
// header('Expect-CT: max-age=86400, enforce, report-uri="/report.php"');
