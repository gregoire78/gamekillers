<?php
//Fonction pour créer un commentaire
function create_commentaire($text_com, $id_article){
	require("connect_bdd.php");//on inclut la connection à la bdd
	$id=$_SESSION['id_user'];
    $query = "INSERT INTO commentaire(text_com, id_user, date_com, id_article)
			  VALUE ('".$text_com."', '".$id."', NOW(), '".$id_article."')";
    $res = mysqli_query($bdd, $query);
	header("Location:#");
	return $res;
}

//Fonction pour récupérer les commentaires d'un article
function recup_commentaire($id_article){
	require("connect_bdd.php");
	$query = "SELECT *
			  FROM commentaire 
	          WHERE id_article='".$id_article."' AND statut_com =1
			  ORDER BY date_com DESC";
	$res= mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour mettre à jour un commentaire
function update_commentaire($text_com, $id_com){
	require("connect_bdd.php");	
	$query="UPDATE commentaire 
			SET text_com='".$text_com."'
	        WHERE id_com='".$id_com."'" ;
	$res= mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour supprimer un commentaire
function delete_commentaire($id_com){
	require("connect_bdd.php");
	$query="UPDATE commentaire 
			SET statut_com = 0
	        WHERE id_com='".$id_com."'" ;
	$res= mysqli_query($bdd, $query);
}

//fonction recuperant les auteurs des articles
function auteurs_commentaires($auteur_com)
{
	require("connect_bdd.php");
	$query = "SELECT * FROM users WHERE id_user='".$auteur_com."'";
	$res=mysqli_query($bdd, $query);
	$data_auteur_com = mysqli_fetch_array($res);
	return $data_auteur_com;
}

//fonction pour récupérer les commentaires poster par l'utilisateur
function recup_commentaire_user(){
	require("connect_bdd.php");
	$id=$_SESSION['id_user'];
	$query = "	SELECT c.id_article, id_com, title, text_com, note_globale, date_com
				FROM commentaire c
			  
				JOIN article a
				ON c.id_article = a.id_article
			  
				WHERE statut_com=1 AND c.id_user =".$id."
				ORDER BY date_com DESC";
				
	$res=mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour récupérer la noter des commentaires des articles et les informations concernant l'auteur du commentaire
function recup_commentaire_noter(){
	require("connect_bdd.php");
	$id=$_SESSION['id_user'];
	$query ="	SELECT e.id_com, note_com, text_com, c.id_user, pseudo, statut, avatar, title, c.id_article, date_com
				FROM evaluer e

				JOIN commentaire c
				ON c.id_com = e.id_com
				
				JOIN users u
				ON c.id_user = u.id_user
				
				JOIN article a
				ON c.id_article = a.id_article

				WHERE e.id_user =".$id." AND statut_com = 1
				
				ORDER BY date_com DESC";
	
	$res = mysqli_query($bdd, $query);
	return $res;
}
?>