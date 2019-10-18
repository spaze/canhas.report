<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

header('Public-Key-Pins: pin-sha256="ljbKIGOBhWbHsgr5ieSGoUd5dbvm3/lQE3wKBs5p6ys="; pin-sha256="WilSR+KkE5qbh2xuw/lwFUDs67VGvP8LX1Tt5zAfp7I="; max-age=600; includeSubDomains; report-uri="' . \Can\Has\reportUrl() . '"');
echo \Can\Has\pageHead('HPKP');
?>
<body>
<div>
<?= \Can\Has\bookmarks('index', 'reports'); ?>
HPKP response header is deprecated and ignored in Chrome now.
</div>
</body>
