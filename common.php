<?php


function do_refresh_button()
{
	echo '<form method="post">';
	echo '<input type="submit" value="Refresh">';
	echo '</form>';
}


function do_duration( $seconds )
// given the duration in seconds, show hh:mm:ss
{
	$ss = $seconds % 60;
	$seconds = floor( $seconds / 60 );
	$mm = $seconds % 60;
	$seconds = floor( $seconds / 60 );
	$hh = $seconds % 24;
	$dd = floor( $seconds / 24 );
	return sprintf('%02s:%02s:%02s:%02s',$dd,$hh,$mm,$ss);
}

function replaceQueryString($var,$val){
     $query_string = $_SERVER["QUERY_STRING"];
     return preg_replace("/$var=[\\d\\w]*/","$var=$val",$query_string);   
}

?>

