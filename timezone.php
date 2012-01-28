<html>

<body>
<?
echo "Original Time: ". date("h:i:s");
echo "<hr>";
//putenv("TZ=US/Eastern");
putenv("TZ=Asia/Calcutta");
echo "New Time: ". date("h:i:s");
?>
<hr>
<?php echo date_default_timezone_get() ?>
<hr>
<p>Last update: <?php echo date(DATE_RFC822); ?></p>

</body>

</html>