<?php

include 'configuration.php';

if(isset($_GET['dossier'])) //si $_GET['dossier'] existe
	{
	if ($_GET['dossier'] == '')
		{
		$adr = $base.'/'.$data.'/vide';
		$non="erreur_de_lien.zip";
		}
		else
		{
		$adr = $base.'/'.$data.'/'.$_GET['dossier'];
		if (isset($_GET['nom']))
			{
			$nom=$_GET['nom'].".zip";
			}
			else
			{
			$nom="erreur_de_lien.zip";
			$adr = "vide";
			}
		}
	}
	else
	{
	$adr = "vide";
	$non="erreur_de_lien.zip";
	}


$rootPath = realpath($adr);

if(file_exists($desti.'/'.$nom)) { 
rename($desti.'/'.$nom, $desti.'/'.date("d-m-Y_ H-i-s").'_'.$nom);
}
// Initialize archive object
$zip = new ZipArchive();
$zip->open($nom, ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();

// On déplace l'archive dans le dossier voulu

if(rename($nom, $desti.'/'.$nom))
	{
	$telechargenent=$desti.'/'.$nom;
	// désactive la mise en cache
	header("Cache-Control: no-cache, must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0");
	header("Cache-Control: max-age=0");
	header("Pragma: no-cache");
	header("Expires: 0");
 
	// force le téléchargement du fichier avec son nom
	header("Content-Type: application/force-download");
	header('Content-Disposition: attachment; filename="'.$nom.'"');
 
	// indique la taille du fichier à télécharger
	header("Content-Length: ".filesize($telechargenent));
 
	//	ob_clean();
	flush();

	// envoi le contenu du fichier
	readfile($telechargenent);
	exit;
	return true;
	}
	else
	{
	echo "<h1 style='color:red;'>L'archive n'a pas été créée</h1>";
	echo 'Verrifier les droits d\'écriture du dossier <span  style=\'font-weight: bold; color:green\'>'.basename(dirname($_SERVER[PHP_SELF])).'</span> où se trouve le fichier zipper.php<br/>';
	echo 'et du dossier: <span  style=\'font-weight: bold; color:green\'>'.basename(dirname($_SERVER[PHP_SELF])).'/'.$destination.'</span><br/>';
	}



?>
