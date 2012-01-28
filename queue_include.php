<?php


function get_all_schedds()
// get list of schedd names
// return an array of strings
{
	$str = `condor_status -schedd -format %s\\n Name`;

	$schedds = Array();

	$tok = strtok($str, "\n");
	$ii = 0;
	while ($tok !== false) {
		$schedds[$ii++]=$tok;
		$tok = strtok("\n");
	}

	return $schedds;
}

function read_data( $schedd )
// read xml data into a matrix
{
	global $g_list;
	global $g_matrix;
	global $g_total_jobs;
	global $g_xml;

	//echo 'Still has ',count( $g_xml->c ),' jobs.<br />';
	
	$num_jobs = count( $g_xml->c );
	for( $row = 0; $row < $num_jobs; $row++ )
	{
		// insert schedd name
		$g_matrix[$g_total_jobs+$row][0] = $schedd;
		// process all attributes
		foreach( $g_xml->c[$row]->a as $attrib )
		{
			$col = array_search( $attrib['n'], $g_list );
			if( $col !== FALSE )
				$g_matrix[$g_total_jobs+$row][$col+1] = $attrib->children();
		}
	}

	$g_total_jobs += $num_jobs;
}

function data_accounting()
{
	global $g_list;
	global $g_matrix;
	global $g_total_jobs;
	global $g_accounting;

	$col = array_search( 'JobStatus', $g_list );
	for( $row = 0; $row < $g_total_jobs; $row++ )
	{
		// JobStatus : Integer which indicates the current status of the job, where 1 = Idle, 2 = Running, 3 = Removed, 4 = Completed, and 5 = Held.
		switch( (int) $g_matrix[$row][$col+1] )
		{
			case 1: $g_accounting[0]++; break;
			case 2: $g_accounting[1]++; break;
			case 3: $g_accounting[2]++; break;
			case 4: $g_accounting[3]++; break;
			case 5: $g_accounting[4]++; break;
		}
	}
}

function print_accounting()
{
	global $g_total_jobs;
	global $g_accounting;

	echo '<table border="0" cellpadding="4" cellspacing="2">';
	echo '<tr class="d2">';
	echo '<th>Total Jobs</th>';
	echo '<th>Idle</th>';
	echo '<th>Running</th>';
	echo '<th>Removed</th>';
	echo '<th>Completed</th>';
	echo '<th>Held</th>';
	echo '</tr>';
	
	echo '<tr class="d0">';
	echo '<td>',$g_total_jobs,'</td>';
	for( $row = 0; $row < 5; $row++ )
	{
		echo '<td>',$g_accounting[$row],'</td>';
	}
	echo '</tr>';
	echo '</table>';
}

function sort_data()
{
}

function format_data()
{
	global $g_list;
	global $g_matrix;
	global $g_total_jobs;
	
	$time_now = time();
	
	for( $row = 0; $row < $g_total_jobs; $row++ )
	{
	
		foreach( $g_list as $col => $attribute )
		{
			$value = $g_matrix[$row][$col+1];

			
			
	switch( (string)$attribute )
	{
	// Add link to for get ClassAd in XML
	case 'ProcId':
		// assemble condor query command
		$cluster_id = $g_matrix[$row][ array_search( 'ClusterId', $g_list ) + 1 ];
		$cmd = 'condor_q -xml -name ' . $g_matrix[$row][0] . ' ' . $cluster_id . '.' . $value;
		$g_matrix[$row][$col+1] = '<a href="command.php?command=' . $cmd . '" target="main" title="Show job ClassAds in XML">' . $value . '</a>';
		break;
	case 'QDate':
		if( ( $value!=0 ) && ( $value !== FALSE ) )
		{
			$g_matrix[$row][$col+1] = date( "M j G:i:s", (int) $value );
		}
		break;
	case 'JobStartDate':
	case 'JobCurrentStartDate':
		// Convert unix timestamp to human readable date format.
		// Create a run time column.
		$idx = array_search( 'JobStatus', $g_list );
		if( ( $value!=0 ) && ($value !== FALSE ) && ($g_matrix[$row][$idx+1] == 'Running') )
		{
			$job_run_time = $time_now - ((int)$value);
			$g_matrix[$row][$col+1] = date( "M j G:i:s", (int) $value ) . '</td><td>' . do_duration($job_run_time);
		}
		else
		{
			$g_matrix[$row][$col+1] = '</td><td>';
		}
		break;
	case 'EnteredCurrentStatus':
		// Convert unix timestamp to human readable date format.
		$g_matrix[$row][$col+1] = date( "M j G:i:s", (int) $value );
		break;
	case 'JobStatus':
	// JobStatus : Integer which indicates the current status of the job, where 1 = Idle, 2 = Running, 3 = Removed, 4 = Completed, and 5 = Held.
		switch( $value ) {
		case 1: $g_matrix[$row][$col+1] = '<b>Idle</b>'; break;
		case 2: $g_matrix[$row][$col+1] = 'Running'; break;
		case 3: $g_matrix[$row][$col+1] = 'Removed'; break;
		case 4: $g_matrix[$row][$col+1] = 'Completed'; break;
		case 5: $g_matrix[$row][$col+1] = '<i>Held</i>'; break;
		}
		break;
	case 'Cmd':
	// Strip the full path download to just the file name.
		$g_matrix[$row][$col+1] = substr(  strrchr( $value, "\\" ), 1  );
		break;
	case 'Rank':
		$g_matrix[$row][$col+1] = sprintf( '%f', $value );
		break;
	case 'RemoteHost':
		// Create a link to the remote execution directory
		if ($value!='') {
			$no_vm_prefix = strstr( $value, '@' );
			if ($no_vm_prefix != FALSE)
				$hostname = substr($no_vm_prefix, 1); // skip '@'
			else
				$hostname = $value;
			$g_matrix[$row][$col+1] = '<a href="file://///' . $hostname . '/condor/execute" target="main" title="Browse execution directory (Firefox users: copy-n-paste this link into the URL box.)">' . $value . '</a>';
		}
		break;
	}



	
	
		} // for attributes
	} // for jobs

} // function format_data

function print_data()
{
	global $g_list;
	global $g_list_length;
	global $g_matrix;
	global $g_total_jobs;

	// print all attributes in a table
	echo '<table border="0" cellpadding="4" cellspacing="2">';
	echo '<tr class="d2">';
	echo '<th>schedd</th>';
	for( $col = 0; $col < $g_list_length; $col++ )
	{
		echo '<th>',$g_list[$col],'</th>';
		// add a column that computes the job running time by subtracting current time with JobStart
		if( $g_list[$col] == 'JobCurrentStartDate' ) echo '<th>JobRunTime</th>';
	}
	echo '</tr>';
	for( $row = 0; $row < $g_total_jobs; $row++ )
	{
		echo '<tr class="d', $row & 1, '">';
		for( $col = 0; $col < 1+$g_list_length; $col++ )
		{
			echo '<td>',$g_matrix[$row][$col],'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}


?>
