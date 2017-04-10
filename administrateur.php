<?php
session_start();
include_once("accessoires/menu.php");

//On vérifie que la personne est administrateur sinon on la redirige vers la page d'accueil
if($statut_menu != 3){
	header("Location:index.php");
}

//On met à jour le statut d'un utilisateur
if (isset($_GET['user'])){
	$id_user_statut = $_GET['user'];
	$statut_user = statut_user($id_user_statut);
	switch ($statut_user){
		case 'Banni' : //Si la personne est banni alors on la réintègre en tant qu'utilisateur
			$rang = 1;
			break;
		case 'Administrateur' : //Si la personne est administrateur alors c'est une erreur donc on redirige vers la page administrateur.php
			header("Location:administrateur.php");
			break;
		default : //Si la personne n'est pas banni alors on la banni
			$rang = 0;
			break;
	}
	
	//On met à jour son rang
	update_statut_user( $rang ,$id_user_statut);
	header("Location:administrateur.php");
}
//On récupère les articles de l'utilisateur
$res = recup_all_user();
$i=0;
while ($data = mysqli_fetch_array($res)){
	$id_user_adm[$i]=$data['id_user'];
	$nom_user_adm[$i]=$data['nom'];
	$prenom_user_adm[$i]=$data['prenom'];
	$email_user_adm[$i]=$data['email'];
	$pseudo_user_adm[$i]=$data['pseudo'];
	$avatar_user_adm[$i]=$data['avatar'];
	$rang_user_adm[$i] = $data['statut'];
	$statut_user_adm[$i]=statut_user($id_user_adm[$i]);
	$i++;
}
include_once("administrateur.html");
?>