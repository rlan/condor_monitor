<?php


function extract_info()
{
	global $g_xml;
	global $g_list;
	global $g_total_machines;

	// extract all computer's attributes
	for( $row = 0; $row < $g_total_machines; $row++ )
	{
		foreach( $g_xml->c[$row]->a as $attrib )
		{
			$col = array_search( $attrib['n'], $g_list );
			if( $col !== FALSE )
				$matrix[$row][$col] = $attrib->children();
		}
	}
	return $matrix;
}

function print_table( $matrix )
{
	global $g_xml;
	global $g_list;
	global $g_total_machines;
	global $g_list_length;
	global $g_time_now;


	// print all attributes in a table
	echo '<table border="0" cellpadding="4" cellspacing="2">';
	//echo '<caption>Details</caption>';
	echo '<tr class="d2">';
	for( $col = 0; $col < $g_list_length; $col++ )
	{
		echo '<th>',$g_list[$col],'</th>';
		// add a column that computes the job running time by subtracting current time with JobStart
		if( $g_list[$col] == 'JobStart' ) echo '<th>JobRunTime</th>';
	}
	echo '</tr>';
	for( $row = 0; $row < $g_total_machines; $row++ )
	{
		echo '<tr class="d', $row & 1, '">';
		for( $col = 0; $col < $g_list_length; $col++ )
		{
			echo '<td>',format_data($g_list[$col], $matrix[$row][$col]),'</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}

function format_data( $attribute, $value )
{
	global $g_time_now;

	// post processing the value
	switch( (string)$attribute )
	{
	case 'Name':
	// Add link to for get ClassAd in XML
		return '<a href="command.php?command=condor_status -xml ' . $value . '" target="main" title="Show ClassAds in XML">' . $value . '</a>';
		break;
	// Add link to for get ClassAd in XML
//	case 'ClusterID':
// TODO: need fix
//		return '<a href="command.php?command=condor_q -xml -global ' . $g_matrix['Owner'][$ii] . ' ' . $value . '" target="main" title="Show ClassAds in XML">' . $value . '</a>';
//		break;
	case 'JobStart':
	//case 'QDate':
	//case 'JobStartDate':
	// Convert unix timestamp to human readable date format.
		//if( $value!=0 )	$value = date( DATE_RFC822, $value );
		if( ( $value!=0 ) && ($value !== FALSE ) )
		{
			$job_run_time = $g_time_now - ((int)$value);
			return date( "M j G:i:s", (int) $value ) . '</td><td>' . do_duration($job_run_time);
		}
		else
		{
			return '</td><td>';
		}
		break;
	case 'JobStatus':
	// JobStatus : Integer which indicates the current status of the job, where 1 = Idle, 2 = Running, 3 = Removed, 4 = Completed, and 5 = Held.
		switch( $value ) {
		case 1: return 'Idle'; break;
		case 2: return 'Running'; break;
		case 3: return 'Removed'; break;
		case 4: return 'Completed'; break;
		case 5: return 'Held'; break;
		}
		break;
	case 'Cmd':
	// Strip the full path download to just the file name.
		return substr(  strrchr( $value, "\\" ), 1  );
		break;
	case 'LoadAvg':
		return sprintf( '%f', $value );
		break;
	case 'State':
		if( $value == "Claimed" )
			return( "<b>".$value."</b>" );
		else
			return $value;
		break;
	case 'Activity':
		if( $value == "Busy" )
			return( "<i>".$value."</i>" );
		else
			return $value;
		break;
	default:
		return $value;
		break;
	}

}



function do_condor_status_total()
{
// parse condor_status -total and turn it into a table

	$string = `condor_status -total`;
	echo '<table border="0" cellpadding="4" cellspacing="2">';
	//echo '<caption>Summary</caption>';
	// header row
	echo '<tr class="d2">';
	echo '<th></th>';
	$tok = strtok($string, " \n\t");
	for( $col = 0; $col < 7; $col++ ) {
		echo '<th>',$tok,'</th>';
		$tok = strtok(" \n\t");
	}
	echo '</tr>';

	// data rows
	$continue = TRUE;
	$row = 0;
	while( $continue ) {
		echo '<tr class="d', $row++ & 1, '">';
		if( ( $tok == 'Total' ) || ($tok === FALSE ) ) $continue = FALSE;
		echo '<td><b>',$tok,'</b></th>';
		$tok = strtok(" \n\t");
		for( $col = 0; $col < 7; $col++ ) {
			echo '<td>',$tok,'</td>';
			$tok = strtok(" \n\t");
		}
		echo '</tr>';
	}
	echo '</TABLE>';
}



?>

