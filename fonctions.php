<?php
include 'configuration.php';


/* extraction taille totale d'un dossier, 
   et calcul du nombre de fichiers et de 
   dossiers contenus */
function getSize($data) {
  global $nfile, $ndir;
  $size = 0;
  /* ouverture */
  if($dir = opendir($data)) {
    /* listage */
    while($entry = readdir($dir)) {
	  /* protection contre boucle infini */
	  if(!in_array($entry, array(".",".."))) {
	    /* cas dossier, récursion */
        if(is_dir($data."/".$entry)) {
	      $size += getSize($data."/".$entry);
		  $ndir++;
		/* cas fichier */
	    } else {
	      $size += filesize($data."/".$entry);
		  $nfile++;
	    }
	  }
	}
	/* fermeture */
	closedir($dir);
  }
  return $size;
}


/* formatage de la taille */
function formatSize($s) {
  /* unités */
  $u = array('octets','Ko','Mo','Go','To');
  /* compteur de passages dans la boucle */
  $i = 0;
  /* nombre à afficher */
  $m = 0;
  /* division par 1024 */
  while($s >= 1) {
    $m = $s;
	$s /= 1024;
	$i++;
  }
  if(!$i) $i=1;
  $d = explode(".",$m);
  /* s'il y a des décimales */
  if($d[0] != $m) {
    $m = number_format($m, 2, ",", " ");
  }
  return $m." ".$u[$i-1];
}

/* formatage du type */
function assocType($type) {
  /* tableau de conversion */
  $t = array(
    'fifo' => "file",
    'char' => "fichier spécial en mode caractère",
    'dir' => "dossier",
    'block' => "fichier spécial en mode bloc",
    'link' => "lien symbolique",
    'file' => "fichier",
    'unknown' => "inconnu"
  );
  return $t[$type];
}

/* description de l'extention */
function assocExt($ext) {
  $e = array(
	'' => "inconnu",
	'doc' => "Microsoft Word",
	'xls' => "Microsoft Excel",
	'xlsx' => "Microsoft Excel",
	'ppt' => "Microsoft Power Point",
	'pdf' => "Adobe Acrobat",
	'zip' => "Archive WinZip",
	'txt' => "Document texte",
	'gif' => "Image GIF",
	'tif' => "Image TIF",
	'bmp' => "Image BMP",
	'jpg' => "Image JPEG",
	'JPG' => "Image JPEG",
	'avi' => "Vidéo AVI",
	'wmv' => "Vidéo WMV",
	'AVI' => "Vidéo AVI",
	'nov' => "Vidéo NOV",
	'MOV' => "Vidéo NOV",
	'pps' => "Diapo PPS",
	'png' => "Image PNG",
	'php' => "Script PHP",
	'php3' => "Script PHP",
	'htm' => "Page web",
	'html' => "Page web",
	'css' => "Feuille de style",
	'js' => "JavaScript",
	'ini' => "Fichier systeme",
	'db' => "Fichier systeme",
	'bat' => "Fichier systeme",
	'dll' => "Dynamic Link Library",
	'DLL' => "Dynamic Link Library",
	'exe' => "Fichier executable"
  );
  if(in_array($ext, array_keys($e))) {
    return $e[$ext];
  } else {
    return $e[''];
  }
}


/* format de type de fichier */
function Delete( $in_Perms ) { 
      
      if(is_file($in_Perms)){	
		unlink($in_Perms);	    
		} else {	 
		    if(is_dir($in_Perms)){	
			$files = array();
		 	$files = ListFile($in_Perms);

		   for($i=1; $i<=$files[0]; $i++)
		   {
			unlink($in_Perms . "/" . $files[$i]);
		    }
		 $dirs = array();	
		 $dirs = ListDir($in_Perms);
			 
	    for($i=1; $i<=$dirs[0]; $i++){	
		  Delete($in_Perms . $dirs[$i]);	
		    }
		    rmdir($in_Perms);	
		   	}		  
		  }
     }


/* infos à extraire */
function infofichier($chemin,$data) {
  $tableau['name'] = $chemin;
  $tableau['size'] = filesize($data."/".$chemin);
  $tableau['date'] = filemtime($data."/".$chemin);
  $tableau['type'] = filetype($data."/".$chemin);
  $tableau['access'] = fileatime($data."/".$chemin);
  $tableau['perms'] = Delete(fileperms($data."/".$chemin));
  $t = explode(".", $chemin);
  $tableau['ext'] = $t[count($t)-1];
  return $tableau;
}
function inforep($chemin,$data) {
  $tableau['name'] = $chemin;
  $tableau['size'] = getSize($data."/".$chemin);
  return $tableau;
}

function explorer_fichier($repertoire) 
{
global $data; //appel des variables qui sont hors de la fonction
// on remplace les caracteres posants problemes
	$a_remplacer = array("..", "/.", "\"", "./", "//");
	$repertoire = str_replace($a_remplacer, "", $repertoire);
if ($dir = opendir($repertoire)) 
	{
	// tableaux
	$rep = array();
	$fichier = array();
	while($var = readdir($dir))
		{
		if(is_dir($repertoire."/".$var)) 
			{
			if(!in_array($var, array(".",".."))) 
				{
				$rep[] = inforep($var,$repertoire);
				}
  			}
		else 
			{
			$fichier[] = infofichier($var,$repertoire);
			}
		}
	}
	sort($rep);
	sort($fichier);
	foreach($rep as $affichage)
		{
		echo '<tr>';
		echo '<td><a href="properties.php?type=dir&dir='.rawurlencode(str_replace($data.'/', "", $repertoire.'/'.$affichage['name'])).'" title="Télécharger" onclick="window.open(this.href,\'\',\'menubar=no,toolbar=no,location=no,status=no,scrollbars=no,resizable=yes,width=430,height=330\'); return false;"><img src="images/dir-close.gif" border=0 />'.$affichage['name'].'</a>';
		echo '<td>'.formatSize($affichage['size']).'</td>';
		echo '<td></td>';
		echo '</tr>';
		}
	foreach($fichier as $affichage)
		{
		echo '<tr>';
		
		echo '<td><a href="properties.php?type=file&dir='.rawurlencode(str_replace($data.'/', "", $repertoire.'/'.$affichage['name'])).'" title="Télécharger" onclick="window.open(this.href,\'\',\'menubar=no,toolbar=no,location=no,status=no,scrollbars=no,resizable=yes,width=430,height=330\'); return false;"><img src="images/file-none.gif" border=0 />'.$affichage['name'].'</a>';
		echo '<td>'.formatSize($affichage['size']).'</td>';
		echo '<td>'.date("d/m/Y", $affichage['date']).'</td>';
		echo '<td>'.assocType($affichage['type']).'</td>';
		echo '<td>'.assocExt($affichage['ext']).'</td>';
		echo '<td>'.date("d/m/Y", $affichage['access']).'</td>';
		//echo '<td>'.$affichage['perms'].'</td>';
		echo '</tr>';
		}
}

function explorer_rep($repertoire, $chemin, $marge=1)
{
	global $data; //appel des variables qui sont hors de la fonction
	$rep = array();
	$le_repertoire = opendir($repertoire) or die("Erreur le repertoire $repertoire existe pas");
	while($var = @readdir($le_repertoire))
	{
	if ($var == "." || $var == "..") continue;
	if(is_dir($repertoire.'/'.$var)) // si c'est un repertoire
		{
		$rep[] = $var;
		}
	}
	sort($rep);
	foreach($rep as $affichage)
	{
	// on remplace les caracteres posants problemes
	$a_remplacer = array("  ", " ", "'", "é", "è", "à", "ç", "//", "/");
	$affichage_simple = str_replace($a_remplacer, "_", $affichage);
	$chemin_simple = str_replace($a_remplacer, "_", $chemin);
	$repertoire_simple = str_replace($a_remplacer, "_", $repertoire);
	// on affiche les repertoires qui contiennent uniquement ceux demandés. 
	if(preg_match('/'.$repertoire_simple.'/', $chemin_simple))
		{
		// marge sur les repertoires
		for($i=1; $i<=(6*$marge); $i++) {
		echo "&nbsp;";
		}
		// Le repertoire est celui demandé
		if($chemin == $repertoire.'/'.$affichage)
			{
			echo '<b style="FONT-WEIGHT: bold"><img src="images/dir-open.gif" />'.$affichage.'</b><br/>';
			} 
		// on met une icone special pour le repertoire qui fait parti du chemin
		elseif(preg_match('/'.$affichage_simple.'/', $chemin_simple))
			{
			echo '<a style="FONT-WEIGHT: bold" href='.$_SERVER['PHP_SELF'].'?dir='.rawurlencode(str_replace($data.'/', "", $repertoire.'/'.$affichage)).'><img src="images/dir-open.gif" border=0 />'.$affichage.'</a><br/>';
			}
			else
			{
			echo '<a href='.$_SERVER['PHP_SELF'].'?dir='.rawurlencode(str_replace($data.'/', "", $repertoire.'/'.$affichage)).'><img src="images/dir-close.gif" border=0 />'.$affichage.'</a><br/>';
			}
		
		explorer_rep($repertoire.'/'.$affichage, $chemin, $marge+1); // fonction recursif pour explorer le nouveau repertoire
		}
        }
    
    closedir($le_repertoire);
}

function open_fenetre()
                        {
                                window.open('pageb.html','nom_de_ma_popup','menubar=no, scrollbars=no, top=100, left=100, width=300, height=200');
                        }

?>