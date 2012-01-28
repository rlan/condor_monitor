<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html>
	<HEAD>
	<!-- <META HTTP-EQUIV=REFRESH CONTENT=5> -->
	<title>Condor Monitor - <?php if(isset($_REQUEST['command']) && !empty($_REQUEST['command']) ) echo $_REQUEST['command']; ?></title>
	</HEAD>
<body>

<?php require_once 'common.php'; ?>

<?php do_refresh_button(); ?>

<?php 
function execute_command($command="") {
	if(isset($command) && !empty($command) ) {
		$output = `$command 2>&1`;
		$output = htmlentities( $output );
		print "<h2>$command</h2>";
		print "<p><pre>$output</pre></p>";
		print "<hr><p>Last update on ";
		print date(DATE_RFC822);
		print "</p>";
	}
}
?>

<?php
	$command = $_REQUEST['command']; // grab user input
	execute_command($command);
?>

<?php do_refresh_button(); ?>

</body>
</html>
