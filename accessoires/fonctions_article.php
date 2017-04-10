<?php
//Fonction pour créer un article
function create_article($title_article, $text_article, $com_allowed, $image_article, $libelle, $statut_menu){
	require("connect_bdd.php");//on inclut la connection à la bdd
	$id=$_SESSION['id_user'];
	
	$id_type=type_article_id($libelle);
	if($statut_menu>1){
		$statut_article=1;
	}else{
		$statut_article=0;
	}
	
    $query = "INSERT INTO article (title, texte, com_allowed, image_title, date_art, id_type, id_user, statut_article) 
			  VALUE ('".$title_article."', '".$text_article."', ".$com_allowed.", '".$image_article."', NOW(), ".$id_type.", ".$id.", ".$statut_article.")";
    mysqli_query($bdd, $query);
}

//Fonction pour récupérer les données d'un type article
function recup_article_type($type){
	require("connect_bdd.php");//on inclut la connection à la bdd
	$query = "SELECT * 
			  FROM article 
			  WHERE id_type='".$type."' AND statut_article = 1
			  ORDER BY date_art DESC";
	$res= mysqli_query($bdd, $query);
	return $res;
}

//Fonction permettant de récupérer les données d'un seule article
function recup_article($id_article){
	require("connect_bdd.php");
	$query = "SELECT * FROM article WHERE id_article='".$id_article."' AND statut_article = 1";
	$res= mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour mettre à jour un article
function update_article($title_article, $text_article, $com_allowed, $image_article, $libelle, $id_article,$statut_user){
	require("connect_bdd.php");
	
	$id=$_SESSION['id_user'];
	
	if ($statut_user==3){
		$statut_article=1;
	}else{
		$statut_article=0;
	}
	
	$id_type=type_article_id($libelle);
	
	$query="	UPDATE article 
				SET title='".$title_article."', texte='".$text_article."', com_allowed='".$com_allowed."', image_title='".$image_article."', id_type='".$id_type."', statut_article=".$statut_article."
				WHERE id_article='".$id_article."'" ;
	mysqli_query($bdd, $query);
}

//Fonction pour supprimer un article
function delete_article($id_article){
	require("connect_bdd.php");
	
	$query="UPDATE article 
			SET statut_article=2
			WHERE id_article='".$id_article."'" ;
	mysqli_query($bdd, $query);
}

//Fonction pour récupérer tout les types d'articles
function recup_libelle(){
	require("connect_bdd.php");
	$query = "SELECT libelle, id_type FROM type";
	$res= mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour récupérer le type de l'article qui correspond à son id
function type_article_libelle($id_type){
	require("connect_bdd.php");
	$query = "SELECT libelle FROM type WHERE id_type='".$id_type."'";
	$res= mysqli_query($bdd, $query);
	$data=mysqli_fetch_array($res);
	$type_article_libelle=$data['libelle'];
	return $type_article_libelle;
}

//Fonction pour récupérer l'id correspondant au type d'article
function type_article_id($libelle){
	require("connect_bdd.php");
	$query = "SELECT id_type FROM type WHERE libelle='".$libelle."'";
	$res= mysqli_query($bdd, $query);
	$data=mysqli_fetch_array($res);
	$type_article_id=$data['id_type'];
	return $type_article_id;
}

//Fonction permettant de récupérer tous les articles sans distinction de type
function recup_article_accueil(){
	require("connect_bdd.php");
	$query = "SELECT * FROM article WHERE statut_article=1 ORDER BY date_art DESC";
	$res= mysqli_query($bdd, $query);
	return $res;
}

//fonction format de la date (de l'article)
function format_date($date)
{
	setlocale (LC_TIME, 'fr_FR.utf8','fra');
	$date=utf8_encode(strftime("%A %d %B %Y &agrave; %Hh%M", strtotime($date)));
	return $date;
}

//fonction pour enregistrer la lecture d'un article
function save_lecture_article($id_article){
	require("connect_bdd.php");
	$id = $_SESSION['id_user'];
	
	$query = "INSERT INTO noter (id_user, id_article, date_lecture) 
			  VALUE (".$id.", ".$id_article.", NOW())";
    $res = mysqli_query($bdd, $query);
}

//Fonction pour récupérer les articles lus par l'utilisateur
function recup_lecture_article(){
	require("connect_bdd.php");
	$id = $_SESSION['id_user'];
	$query = "SELECT * 
			  FROM noter
			  WHERE id_user = ".$id."
			  ORDER BY date_lecture DESC";
	$res= mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour savoir un article a été lu
function recup_lecture_user($id_article){
	require("connect_bdd.php");
	$lecture = 0 ;
	if (isset ($_SESSION['id_user'])){
		$id = $_SESSION['id_user'];
		$query = "SELECT *
				  FROM noter
				  WHERE id_article=".$id_article."
				  AND id_user = ".$id."";				  
		$res= mysqli_query($bdd, $query);
		if(mysqli_num_rows($res)==1){
			$lecture=1;
		}
	}
	return $lecture;
}

//Fonction pour récupérer les données d'un article pour mon historique dans le profil
function historique(){
	require("connect_bdd.php");
	$id = $_SESSION['id_user'];
	
	$query = "SELECT n.id_article, date_lecture, title, date_art, libelle, pseudo, avatar
			  FROM noter n
			  
			  JOIN article a
			  ON n.id_article = a.id_article
			  
			  JOIN type t
			  ON t.id_type = a.id_type
			  
			  JOIN users u
			  ON u.id_user = a.id_user
			  
			  WHERE n.id_user =".$id." AND statut_article = 1
			  ORDER BY date_lecture DESC";
			  
	$res= mysqli_query($bdd, $query);
	return $res;
}
//Fonction pour récupérer les articles de l'utilisateur
function recup_article_user($id_user,$statut){
	require("connect_bdd.php");

	$query = "SELECT id_article, title, date_art, libelle, note_totale
			  FROM article a 

			  JOIN type t
			  ON t.id_type = a.id_type

			  WHERE id_user=".$id_user." AND statut_article =".$statut."
			  ORDER BY date_art DESC";
			  
	$res = mysqli_query($bdd, $query);
	return $res;
}

//Fonction pour récupérer les articles que l'utilisateur à noter et les information concernant l'auteur de l'article
function recup_article_noter(){
	require("connect_bdd.php");
	$id = $_SESSION['id_user'];
	
	$query = "	SELECT n.id_article, note_note, title, libelle, pseudo, statut, a.id_user, avatar
				FROM noter n

				JOIN article a
				ON a.id_article = n.id_article

				JOIN type t
				ON t.id_type = a.id_type
				
				JOIN users u
				ON u.id_user = a.id_user

				WHERE n.id_user =".$id." AND statut_article=1 AND note_note!='NULL'
				
				ORDER BY date_art DESC";
				
	$res = mysqli_query($bdd, $query);
	return $res;
}

//la fonction qui recupere tout les articles non verifiées par l'administrateur
function recup_articles_non_valide()
{
	require("connect_bdd.php");
	$query = "SELECT a.id_article, a.texte, a.title, a.date_art, t.libelle , u.pseudo, u.statut, a.id_user, u.avatar
			  FROM article a

			  JOIN type t
			  ON t.id_type = a.id_type

			  JOIN users u
			  ON u.id_user = a.id_user

			  WHERE statut_article=0
			  ORDER BY a.date_art DESC";

	$res = mysqli_query($bdd, $query);
	return $res;
}

//la fonction qui va update le statut à 1 pour qu'il soit validé et donc affichable
//et ésgalement changer le statut à 2 de l'auteur à rédacteur
//et update de la date article pour la date du poste de publication officiel
function update_article_user_statut_valide($id_article)
{
	require("connect_bdd.php");
	$query = "UPDATE article a

			  JOIN users u
			  ON a.id_user = u.id_user

			  SET a.statut_article=1 , u.statut=2 ,a.date_art=NOW()
			  WHERE id_article='".$id_article."'";
	mysqli_query($bdd, $query);
}
?>