
<?php

 	if(isset($_POST['nom'])) //si le nom existe pas
	 	{
			$nom_doc = $_POST['nom'];
			$path = 'partage'.'/'.$nom_doc.'';
			mkdir($path, 0777, true);
		}
			

	 //suppresion 
	 	if (isset($_POST['supprimer'])) 
	 	{
	 		$doc=$_POST['doc'];
			$sup = 'partage'.'/'.$doc.'';
	 		if (isset($sup)) {
	 			if (is_dir($sup)){
	 				rmdir($sup);
	 			}
	 			else{
	 				unlink($sup);
	 			}
	 		}
	 		header('location:index.php');
	 	}
	  ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Naviguer et Télécharger</title>
	<link rel="stylesheet" media="all" type="text/css" href="monstyle.css"/>
</head>
<body>
<br>
<?php

include 'configuration.php';
include 'fonctions.php';

if(isset($_GET['dir'])) //si $_GET['dir'] existe
{
$dir = $_GET['dir'];
}
else
{
$dir = "";

}
$chemin = $data.'/'.$dir;
?>
	<div class="contenu">
		<div class="bleu-italic">
		<h1>
		Naviguer et télécharger
		</h1>
		</div><!--bleu-italic-->
		<div class="droit">
			<span id="google">
				<FORM method=GET action="http://www.google.fr/search">
				<INPUT TYPE=text name=q size=31 maxlength=255 value="">
				<INPUT TYPE=hidden name=hl value=fr>
				<INPUT type=submit name=btnG VALUE="Recherche Google">
				</FORM>
			</span><!--Google -->
		</div><! --droit-->
		<div class="gauche">
				<span id="delete">
				<FORM method=POST>
				<INPUT TYPE=text name=doc size=31 maxlength=255 >
				<button name="supprimer">Supprimer</button>
				</FORM>
			</span>
		</div><! --gauche-->
		<div class="main">
		<div class="tableau">
		<span class="plein">
			<table width="100%" border="0" cellspacing="0">
				<tr><td>
					<div class="centrer">
					- Naviguer<img src="images/Fleche-G_B.png" /><br/><br/><br/><br/>
					</div>
					<?php
						echo '<a href='.$_SERVER['PHP_SELF'].'><img src="images/dir-close.gif" border=0 />&nbsp;/</a><br/>';
						explorer_rep($data, $chemin, 1);
					?>
				</td>
				<td>
					- Télécharger <img src="images/Fleche-bas.png" /><br/><br/>
				<table width="100%" border="0" cellspacing="0">
					<tr>
						<th>Nom</th>
						<th>Taille</th>
						<th>Dernière modif</th>
						<th>Type</th>
						<th>Extention</th>
						<th>Dernier accès</th>
						<th>Permission</th>
					</tr>

					<?php
						explorer_fichier($chemin);
					?>

				</table>
				</td></tr>
			</table>
		</span><!--plein-->
		</div><!--tableau-->
		<div id="cacher">
			<?php include 'footer.php'; ?>
		</div>
		</div><!--main-->
	</div><!--contenu -->
	<!--bar -->

	<div class="taskbar">
			 <div class="icons">
					 <div class="icons-left">
						 <form class=""  method="post">
							 <input type="text" class="nom_doc" name="nom" placeholder="Nom du dossier">
							<button  id="start-menu">Créer un dossier</button>
						 </form>
						 
					 </div>
			 </div>
	 </div>
</body>
</html>
