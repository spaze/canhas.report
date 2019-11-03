<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

header("X-XSS-Protection: 1; report=" . \Can\Has\reportUrl());
echo \Can\Has\pageHead('XSS Auditor');
?>
<body>
<div>
<?= \Can\Has\bookmarks('index', 'reports'); ?>
<div>Chrome was the only browser with XSS Auditor reporting, and XSS Auditor was fully removed from Chrome in 78.</div>
<?= $_GET['echo'] ?? $_POST['echo'] ?? ''; ?>
<a href="?echo=<script>alert(1)</script>">Trigger the XSS Auditor (GET)</a>
<br><br>
then
<br><br>
<form action="xss-auditor.php" method="post">
	<input type="text" name="echo" value="<script>alert(1);</script>">
	<input type="submit" value="Trigger the XSS Auditor (POST)">
</form>
</div>
</body>
