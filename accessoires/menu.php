<?php 
include_once("fonctions_user.php");
include_once("fonctions_article.php");
auto_connexion(NULL,NULL);
$statut_menu=0;
//recuperation données utilisateur
if(isset($_SESSION['id_user']))
{
	$id=$_SESSION['id_user'];
	$data_menu = recup_data_user($_SESSION['id_user']);
	$avatar_menu = $data_menu['avatar'];
	$pseudo_menu = $data_menu['pseudo'];
	$statut_menu = $data_menu['statut'];
	$statut_user=statut_user($id);
}
$res=recup_libelle();
$i=0;
while ($data=mysqli_fetch_array($res)){
	$libelle[$i]=$data['libelle'];
	$id_type[$i]=$data['id_type'];
	$i++;
}
?>
