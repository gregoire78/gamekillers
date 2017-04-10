<?php
session_start();

//On inclu le fichier du menu.php et les fichiers contenant les fonctions utiliser dans le code
include_once("accessoires/menu.php");
include_once("accessoires/fonction_tag_substr.php");

//On vérifie si l'utilisateur est déjà conecter
auto_connexion(NULL,NULL);

//On récupère l'id de l'article dans l'URL
if(isset($_GET['type']))
{
	$type=$_GET['type'];
}
else
{
	header('Location:index.php');
}

//On récupère le libellé corespondant au à l'id de l'article
$type_article=type_article_libelle($type);

//On récupère les articles correspondant à l'id de l'article
$res = recup_article_type($type);

//On parcourt les données récupéré pour les mettre dans un tableau
$i=0;
while ($data=mysqli_fetch_array($res)){
	$id_article[$i]=$data['id_article'];
	$title[$i]=$data['title'];
	$texte[$i]=html_cut($data['texte'],1000)."...";
	$staut_article[$i]=$data['statut_article'];
	$note_totale[$i]=$data['note_totale'];
	$com_allowed[$i]=$data['com_allowed'];
	$image_title[$i]=$data['image_title'];
	$date_art[$i]=format_date($data['date_art']);
	$id_user[$i]=$data['id_user'];
	$i++;
}

//On parcourt le tableau d'id_user pour récupérer le pseudo correspondant
$i=0;
while(isset($id_user[$i])){
	$data=recup_data_user($id_user[$i]);
	$pseudo[$i]=$data['pseudo'];
	$i++;
}

//Si on essaye de supprimer un article depuis l'URL
if(isset($_GET['supprimer'])){
	$supprimer = $_GET['supprimer'];
	$data=mysqli_fetch_array(recup_article($supprimer));
	$id_user_article_bis=$data['id_user'];
	if($statut_menu>3 && $id_user_article_bis!=$id){
		header("Location:actu.php?type=".$type);
	}
}

//Si on valide la suppression
if(isset($_POST['valider_supprimer'])){
	$supprimer = $_GET['supprimer'];
	delete_article($supprimer);
	header("Location:actu.php?type=".$type);
}

//On inclu le fichier html pour la mise en forme
include_once("actu.html");
?>