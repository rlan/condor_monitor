<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<body>
<?php require_once 'common.php'; ?>

<?php function show_form($value="") {  ?>

	<?php do_refresh_button(); ?>

	<form method="post">
	condor_status
	<input type=text name=value value="<?php echo $value?>">
	<input type=submit>
	</form>
	<?php }  // close function  
	$value = $_REQUEST['value']; // grab user input
	if(!isset($value)) {
		show_form();
	}
	else {
        show_form($value);
		if(empty($value)) {
			$output = `condor_status 2>&1`;
		}
		else {
			$output = `condor_status $value 2>&1`;
		}
		$output = htmlentities( $output );
		print "<pre>$output</pre>";
		print "<hr><p>Last update on ";
		print date(DATE_RFC822);
		print "</p>";
	}
	?>

	<?php do_refresh_button(); ?>

</body>
</html>