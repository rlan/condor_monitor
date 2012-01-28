<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
"http://www.w3.org/TR/html4/frameset.dtd">

<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Condor Monitor</title>
	</head>

<?php require_once 'common.php'; ?>

<?php
if (isset($_GET['gmt'])) {
	// manually set time zones
	// Los Angeles is GMT-8:00
	// Detect a range to account for day-light saving
	if( ($_GET['gmt']>=7) AND ($_GET['gmt']<=9) )
		putenv("TZ=America/Los_Angeles");
	else
		putenv("TZ=Asia/Calcutta");

} else {
	// pass time zone info
	// (preserve the original query string
	//  -- post variables will need to handled differently)

	echo "<script language='javascript'>\n";
	echo "  var visitor = new Date();\n";
	echo "  location.href=\"${_SERVER['SCRIPT_NAME']}?${_SERVER['QUERY_STRING']}"
            . "&admin=&gmt=\" + visitor.getTimezoneOffset()/60;\n";
	echo "</script>\n";
	exit();
}
?> 


<frameset cols="20%,80%">

  <frame name="sidebar" src="sidebar.php?<?php echo $_SERVER['QUERY_STRING']; ?>">
  <frame name="main" src="queue.php?<?php echo $_SERVER['QUERY_STRING']; ?>">

</frameset>

</html>
