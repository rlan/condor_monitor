<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
"http://www.w3.org/TR/html4/frameset.dtd">

<html>
	<HEAD>
	<!-- <META HTTP-EQUIV=REFRESH CONTENT=5> -->
	<!-- <title>Condor Pool Monitor</title> -->
	<link rel="stylesheet" href="style.css">
	<title>STATUS - Condor Monitor</title>
	</HEAD>
	<body>
		<?php
		require_once 'common.php';
		require_once 'status_include.php';
		?>

		<?php do_refresh_button(); ?>


		<h1>Execute machines</h1>


		<?php do_condor_status_total();	?>
		
		<hr>
		
		<?php
		$g_time_now = time();

		// list Condor attributes to be printed
		$g_user_list = array( 
			"Name",
			"State",
			"Activity",
			"LoadAvg",
			"ClientMachine",
			"RemoteUser",
			"JobId",
			"JobStart"
		);
		$g_admin_list = array(
//			"StartdIpAddr",
//			"RemoteOwner",
			"SCVersion",
			"MyAddress",
			"Arch",
			"OpSys",
			"VirtualMemory",
			"Disk",
			"Memory",
			"KFlops",
			"Mips",
			"CondorVersion"
//			"SC_READY",
//			"VC_READY"
		);
		if(isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ) {
			$g_list = array_merge( $g_user_list, $g_admin_list );
		} else {
			$g_list = $g_user_list;
		}
		$g_list_length = count( $g_list );
		
		$str = `condor_status -xml`;
		//echo "count = " . strlen($str);
		
		$g_xml = simplexml_load_string( $str );
		$g_total_machines = count($g_xml->c);

		$g_matrix = extract_info();
		print_table( $g_matrix );
		?>
		<hr>
		<p>Last update: <?php echo date(DATE_RFC822); ?></p>

		<?php do_refresh_button(); ?>
		
	</body>
</html>
