<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
"http://www.w3.org/TR/html4/frameset.dtd">

<html>
<body>
<?php require_once 'common.php'; ?>


<h1>Condor Monitor</h1>
<p>
<a href="status.php?<?php echo $_SERVER['QUERY_STRING']; ?>" target="main" title="Show details of execution machines">Status</a> | 
<a href="queue.php?<?php echo replaceQueryString("user",""); ?>" target="main" title="Show details of global queue">Queue</a>
<?php
if(isset($_REQUEST['user']) && !empty($_REQUEST['user']) ) {
	echo ' | ',$_REQUEST['user'],'\'s <a href="queue.php?',$_SERVER['QUERY_STRING'],'" target="main" title="Show details of user queue">queue</a> ';
}
?>
</p>

<hr>

<p>
<a href="http://angeles-print/condorwiki/" title="Get help on Condor">Wiki</a> | 
<a href="../rrdtool/" title="Visualize pool usage" target="main">Usage</a> 
<?php if(isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ) { ?>
	| <a href="index.php?<?php echo replaceQueryString("admin",""); ?>" target="_top" title="Toggle to simple mode">Simple</a> 
<?php } else { ?>
	| <a href="index.php?<?php echo replaceQueryString("admin","1"); ?>" target="_top" title="Toggle to advanced mode">Advanced</a> 
<?php } ?>
</p>

<hr>

<p>
<a href="condor_status.php?value=" target="main">condor_status</a>
<ul>
<li><a href="command.php?command=condor_status -submitters" target="main">-submitters</a></li>
<li><a href="command.php?command=condor_status -schedd" target="main">-schedd</a></li>
<li><a href="command.php?command=condor_status -master" target="main">-master</a></li>
<?php if(isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ) { ?>
<li><a href="command.php?command=condor_status -any" target="main">-any</a></li>
<li><a href="command.php?command=condor_status -claimed" target="main">-claimed</a></li>
<li><a href="command.php?command=condor_status -negotiator" target="main">-negotiator</a></li>
<li><a href="command.php?command=condor_status -server" target="main">-server</a></li>
<li><a href="command.php?command=condor_status -state" target="main">-state</a></li>
<?php } ?>
</ul>
</p>

<p>
<a href="condor_q.php" target="main">condor_q</a>
<ul>
<?php
if(isset($_REQUEST['user']) && !empty($_REQUEST['user']) ) {
	echo '<li><a href="command.php?command=condor_q -global ',$_REQUEST['user'],'" target="main">-global ',$_REQUEST['user'],'</a></li>';
	echo '<li><a href="command.php?command=condor_q -global -analyze ',$_REQUEST['user'],'" target="main">-global -analyze ',$_REQUEST['user'],'</a></li>';
}
?>
<li><a href="command.php?command=condor_q -global" target="main">-global</a></li>
<li><a href="command.php?command=condor_q -global -analyze" target="main">-global -analyze</a></li>
<?php if(isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ) { ?>
<li><a href="command.php?command=condor_q -global -run" target="main">-global -run</a></li>
<li><a href="command.php?command=condor_q -global -hold" target="main">-global -hold</a></li>
<?php } ?>
</ul>
</p>

<p>
<a href="command.php?command=condor_userprio" target="main">condor_userprio</a>
<ul>
<?php if(isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ) { ?>
<li><a href="command.php?command=condor_userprio -all" target="main">-all</a></li>
<?php } ?>
<li><a href="command.php?command=condor_userprio -all -allusers" target="main">-all -allusers</a></li>
</ul>
</p>

<!-- feature not working, a Condor credential problem
<p>
<a href="condor_rm.php" target="main">condor_rm</a>
</p>
-->

<hr>
[<a href="http://angeles-print/condor/?<?php echo $_SERVER['QUERY_STRING']; ?>" target="_top" title="Goto Los Angeles">Los Angeles</a>] 
[<a href="http://nbs40/condor/?<?php echo $_SERVER['QUERY_STRING']; ?>" target="_top" title="Goto Noida1">Noida1</a>] 
[<a href="http://nbs1/condor/?<?php echo $_SERVER['QUERY_STRING']; ?>" target="_top" title="Goto Noida2">Noida2</a>] 


<hr>

<p>Rick Lan &copy; 2006</p>

		<p>
		<a href="http://validator.w3.org/check?uri=referer">
		<img style="border:0;width:88px;height:31px"
        src="http://www.w3.org/Icons/valid-html401"
        alt="Valid HTML 4.01 Strict"></a>
		<a href="http://jigsaw.w3.org/css-validator/">
		<img style="border:0;width:88px;height:31px"
		src="http://jigsaw.w3.org/css-validator/images/vcss" 
		alt="Valid CSS!" /></a>
		</p>

</body>


</html>
