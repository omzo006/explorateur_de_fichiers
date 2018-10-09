

<!DOCTYPE html>
<html lang="fr-fr">
<head>
	<title>Proprietes</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<style type="text/css">
		body {
			background-color:#cccccc;
			text-align: center;
			}
		span {
			display: block;
			margin : auto;
			}
		button {
			margin : 20px 10px 0px 10px;
			height: 30px;
			width: 120px;
			border-radius: 8px;
			-webkit-transition-duration: 0.4s; /* Safari */
			transition-duration: 0.4s;
			border: 2px solid green
			}
		button:hover {
			background-color: #4CAF50; /* Green */
			color: white;
			box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
			}
	</style>
</head>
<body>
<?php

include 'configuration.php';

$type = $_GET['type'];
$dir = $_GET['dir'];
$entry = $data.'/'.$dir;

if ($dir=='')
	$entry='';

if (stristr($entry,"/.") == false)
	$entry=$entry;
else
	$entry='';


require_once 'fonctions.php';


/* dossier */
function printDir() {
  global $data, $entry, $nfile, $ndir;
  
  /* extraction infos */
  $nfile = 0;
  $ndir = 0;
  $entry = $entry;
  $n = explode("/", $entry);
  $name = $n[count($n)-1];
  $type = assocType(filetype($entry));
  $date = date("d/m/Y H:i:s", filemtime($entry));
  $size = formatSize(getSize($entry));
 
  
  /* affichage */
  echo "<table width=\"100%\" height=\"100%\" 
  border=\"1\" bordercolor=\"gray\" 
  cellspacing=\"0\" cellpadding=\"5\">
  <tr><td align=\"center\" valign=\"middle\"><table>
  <tr><td><img src=\"/web/images/ico-dossier.gif\" alt=\"Dossier\" /></td><td>$name</td></tr>
  <tr><td>Type :&nbsp;</td><td>$type</td></tr>
  <tr><td>Téléchargement :&nbsp;</td><td><a href=\"zipper.php/?dossier=".str_replace($data.'/', "", $entry)."&nom=$name\">$name</a></td></tr>
  <tr><td>Taille :&nbsp;</td><td>$size</td></tr>
  <tr><td>Contenu :&nbsp;</td><td>$nfile fichiers, $ndir dossiers</td></tr>
  <tr><td>Dernière modification :&nbsp;</td><td>$date</td></tr>
  </table></td></tr>
  </table>
  <span> 
<button onclick=\"telecharger()\">Télécharger</button>

<script>
function telecharger() {
	window.open(\"zipper.php/?dossier=".rawurlencode(str_replace($data.'/', "", $entry))."&nom=".rawurlencode($name)."\", \"_self\");
	setTimeout(window.close,20000);
}
</script>
  ";
}

/* fichier */
function printFile() {
  global $entry, $data;
  
  /* extraction infos */
  $entry = $entry;
  $n = explode("/", $entry);
  $name = $n[count($n)-1];
  $type = assocType(filetype($entry));
  $date = date("d/m/Y H:i:s", filemtime($entry));
  $size = formatSize(filesize($entry));
 // $perms = mfunGetPerms(fileperms($entry));
  $access = date("d/m/Y", fileatime($entry));
  $t = explode(".", $entry);
  $ext = assocExt($t[count($t)-1]);
  //echo $entry;
  /* affichage */
  echo "<table width=\"100%\" height=\"100%\" 
  border=\"1\" bordercolor=\"gray\" 
  cellspacing=\"0\" cellpadding=\"5\">
  <tr><td align=\"center\" valign=\"middle\"><table>
  <tr><td><img src=\"/web/images/ico-none.gif\" alt=\"Fichier\" /></td><td>$name</td></tr>
  <tr><td>Type :&nbsp;</td><td>$type</td></tr>
  <tr><td>Téléchargement :&nbsp;</td><td><a href=\"telecharger.php?dir=".rawurlencode(str_replace($data.'/', "", $entry))."\", \"_self\">$name</a></td></tr>
  <tr><td>Taille :&nbsp;</td><td>$size</td></tr>
  <tr><td>Extention :&nbsp;</td><td>$ext</td></tr>
  <tr><td>Dernière modification :&nbsp;</td><td>$date</td></tr>
  <tr><td>Dernier accès :&nbsp;</td><td>$access</td></tr>
 
  </table></td></tr>
  </table>
  <span> 
<button onclick=\"telecharger()\">Télécharger</button>

<script>
function telecharger() {
	window.open(\"telecharger.php?dir=".rawurlencode(str_replace($data.'/', "", $entry))."\", \"_self\");
	setTimeout(window.close,20000);
}
</script>

  ";
}

switch($type) {
  case 'dir' : printDir(); break;
  case 'file' : printFile(); break;
}
?>
	<button onclick="window.close()">Fermer le popup</button>
</span>
</body>
</html>