<?php

include 'configuration.php';

$dir = $_GET['dir'];

$entry = $data.'/'.$dir;

if ($dir=='')
	$entry='';

if (stristr($entry,"/.") == false)
	$entry=$entry;
else
	$entry='';

  $n = explode("/", $entry);
  $name = $n[count($n)-1];


	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header("Content-Disposition: attachment; filename=\"$name\"");
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($entry));
	// ob_clean();
	flush();
	readfile($entry);
	window.close();
	exit;

?>
