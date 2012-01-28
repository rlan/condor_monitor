<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
"http://www.w3.org/TR/html4/frameset.dtd">

<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Get Query</title>
	</head>

<body>
<?php
echo "<hr>";
echo "count = ",count($_GET);
?>
<?php
echo "<hr>";

print_r($_GET);
?>
<?php
echo "<hr>";

foreach ($_GET as $k => $v) {
   echo "<p>\$_GET[$k] => $v.\n";
}
?>
<?php
echo "<hr>";

$query = "";
$isFirst = true;
foreach ($_GET as $k => $v) {
	if( $isFirst ) {
		$query = $query . $k . "=" . $v;
		$isFirst = false;
	} else {
		$query = $query . "&" . $k . "=" . $v;
	}
		
}
echo $query;
?>

<hr>
<a href="showquery.php?<?php echo $query; ?>">Past</a>


</body>

</html>

