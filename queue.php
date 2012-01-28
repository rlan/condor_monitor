<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
"http://www.w3.org/TR/html4/frameset.dtd">

<html>
	<HEAD>
	<!-- <META HTTP-EQUIV=REFRESH CONTENT=5> -->
	<!-- <title>Condor Pool Monitor</title> -->
	<link rel="stylesheet" href="style.css">
	<title><?php
		if(isset($_REQUEST['user']) && !empty($_REQUEST['user']) )
			echo $_REQUEST['user']."'s queue - Condor Monitor";
		else
			echo "QUEUE - Condor Monitor";
	?></title>
	</HEAD>
	<body>
		<?php
		require_once 'common.php';
		require_once 'queue_include.php';
		?>

		<?php do_refresh_button(); ?>


		<h1><?php
		if(isset($_REQUEST['user']) && !empty($_REQUEST['user']) )
			echo $_REQUEST['user']."'s queue";
		else
			echo "Global queue";
		?></h1>
		<?php

		// Algorithm
		// define attribute list
		// for each schedd
		// get xml data
		// read data into matrix
		// format data
		// print data
		// next schedd

		$g_user_list = array(
			"ClusterId",
			"ProcId",
			"Owner", // recommendation: keep uncommented
			//"NTDomain",
			"JobStatus",
			"QDate",
			//"JobStartDate", // check job is running and then use this time to calculate how long the job has been running, first running start timestamp
			"JobCurrentStartDate", // check job is running and then use this time to calculate how long the job has been running, current running start timestamp
			"RemoteHost",
			//"Rank",
			"JobPrio",
			"SC_JOB_DESCRIPTION"
		);
		$g_admin_list = array(
			"EnteredCurrentStatus",
			"JobRunCount",
			"Iwd"
		);
		if(isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ) {
			$g_list = array_merge( $g_user_list, $g_admin_list );
		} else {
			$g_list = $g_user_list;
		}
		$g_list_length = count( $g_list );
		
//		$g_schedds = get_all_schedds();
		$g_schedds = Array();
		$g_matrix = Array();
		$g_total_jobs = 0;
		$g_accounting = Array(0,0,0,0,0);
		
		$cmd = "condor_q -xml -global";
		if(isset($_REQUEST['user']) && !empty($_REQUEST['user']) )
			$cmd = $cmd . " " . $_REQUEST['user'];
		$raw = `$cmd`;

		$pieces = explode( "-- Schedd: ", $raw );
		for( $ii = 1; $ii < count($pieces); $ii++ )
		{
			// strip the schedd name and ip line
			$pos = strpos( $pieces[$ii], ':' );
			$g_schedds[$ii-1] = trim( substr( $pieces[$ii], 0, $pos ) );

			$pos = strpos( $pieces[$ii], '<?xml' );
			$output = substr( $pieces[$ii], $pos );
		
			$g_xml = simplexml_load_string( $output );
			//echo $schedd,' has ',count( $g_xml->c ),' jobs.<br />';
			if( $g_xml !== false )
			{
				read_data( $g_schedds[$ii-1] );
			}
			else
			{
				echo '<h2>Error getting xml data from schedd: ',$schedd,'</h2>';
				echo '<h3>',$cmd,'</h3>';
				echo '<pre>',htmlentities($output),'</pre>';
			}
		}
		data_accounting();
		print_accounting();
		echo '<hr />';
		sort_data();
		format_data();
		print_data();

		?>

		<hr>
		<p>Last update: <?php echo date(DATE_RFC822); ?></p>


		<?php do_refresh_button(); ?>
	
	
	</body>
</html>
