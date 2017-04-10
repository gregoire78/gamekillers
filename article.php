<?php
session_start();

//On inclu le fichier menu.php et les fichiers contenants les fonctions utiliser dans le code
include_once("accessoires/menu.php");
include_once("accessoires/fonctions_commentaire.php");
include_once("accessoires/fonctions_noter_evaluer.php");

//On vérifie si l'utilisateur est connecté.
auto_connexion(NULL,NULL);

setlocale (LC_TIME, 'fr_FR.utf8','fra');

//On récupère l'id de l'article dans l'URL
if(isset($_GET['article']))
{
	$id_article=(int)$_GET['article'];
	if(empty($id_article))
	{
		header('Location:index.php');
	}
}
else
{
	header('Location:index.php');
}


//Evaluer un commentaire
if(isset($_GET['evaluer']) && isset($_SESSION['id_user']) ){
	$id_com_user=$_GET['commentaire'];
	$eval_com_user=recup_eval_user($id_com_user);
	$new_eval_user=$eval_com_user;
	
	switch ($_GET['evaluer']){
		case 'moins' :
			$new_eval_user = -1;
			break ;
		case 'plus'	:
			$new_eval_user = 1;
			break;
		default :
			header("Location:article.php?article=".$id_article);
			break;
	}	
	
	if($eval_com_user!=0){
		update_evaluer_user($id_com_user, $new_eval_user);
	}else{
		evaluer_user($id_com_user, $new_eval_user);
	}	
	header("Location:article.php?article=".$id_article);
}

//Si on valide la suppression de l'article
if(isset($_POST['valider_supprimer']))
{
	delete_article($id_article);
	header("Location:index.php");
}

//si on supprime un commentaire
if(isset($_POST['valider_supprimer_comm']) && isset($_GET['commentaire']))
{
	$id_com= real_scape(trim($_GET['commentaire']));
	delete_commentaire($id_com);
	header("Location:article.php?article=".$id_article);
}

//si on modifi un commentaire
if(isset($_POST['modifier_commentaire']) && isset($_GET['modifecom']))
{
	$id_com=$_GET['modifecom'];
	$new_text_com = real_scape(trim($_POST['nnew_text_commentaire']));
	if(!empty(strip_tags($new_text_com,'<img>')))
	{
		update_commentaire($new_text_com, $id_com);
		header("Location:article.php?article=".$id_article);
	}
}

//creer un commentaire
if(isset($_POST['commenter']))
{
	$text_com = real_scape(trim($_POST['text_commentaire']));
	if(empty($text_com))
	{
		$error_commentaire = "Veuillez saisir un commentaire";
	}
	else
	{
		create_commentaire($text_com, $id_article);
	}
}

//On récupère les données en base de donnée
$res=recup_article($id_article);

//On vérifie que l'article existe
if(mysqli_num_rows($res)==1){
	$existe = true ;
	$data=mysqli_fetch_array($res);
	$title=$data['title'];
	$texte=$data['texte'];
	$com_allowed=$data['com_allowed'];
	$type_article=$data['id_type'];
	$image_article=$data['image_title'];
	$id_user_article=$data['id_user'];
	$date_art=format_date($data['date_art']);
	$note_article=$data['note_totale'];

	//recuperation pseudo
	$data=recup_data_user($id_user_article);
	$auteur_article=$data['pseudo'];
	$auteur_avatar =$data['avatar'];
	//recuperation du statut de l'auteur
	$statut_aut = statut_user($id_user_article);

	//Récupération de la note de l'utlisateur sinon initialisation à 0
	$note_user_article=recup_note_user($id_article);

	//recuperation des commentaires
	$res = recup_commentaire($id_article);
	$nbcom = mysqli_num_rows($res);
	$pi=0;
	while($data_com = mysqli_fetch_array($res))
	{
		$id_com[$pi]=$data_com['id_com'];
		$commentaire[$pi]=$data_com['text_com'];
		$auteur_commentaire[$pi]=$data_com['id_user'];
		$date_commentaire[$pi]=format_date($data_com['date_com']);
		$statut_aut_com[$pi] = statut_user($auteur_commentaire[$pi]);
		$note_globale[$pi] = $data_com['note_globale'];
		//recuperation des données de l'auteur
		$data_auteur_com=auteurs_commentaires($auteur_commentaire[$pi]);
		$pseudo_aut_com[$pi]=$data_auteur_com['pseudo'];
		
		//recupération des notes des commentaires de l'utilisateur (-1, 1 ou 0 si pas encore noter)
		$note_com_user[$pi] = recup_eval_user($id_com[$pi]);
		
		if($id_user_article==$auteur_commentaire[$pi])
		{
			$edito_auteur[$pi] = "(Auteur)";
		}
		$avatar_aut_com[$pi]=$data_auteur_com['avatar'];
		$pi++;
	}

	//On vérifie si l'utilisateur a lu l'article sauf si c'est l'auteur
	if(isset($_SESSION['id_user'])){
		if ($id_user_article!=$id){
			$lecture = recup_lecture_user($id_article);
			if($lecture == 0){
				save_lecture_article($id_article);
			}
		}
	}

	//ajout de l'edito auteur si l'auteur fait un commentaire
	if(isset($_SESSION['id_user']) && $id_user_article == $id)
	{
		$plus = "(Auteur)";
	}
}else{
	$existe = false;
}

//Noter un article
if(isset($_GET['note']) && isset($_SESSION['id_user'])){
	if($id_user_article==$id){
		header("Location:article.php?article=".$id_article);
	}else{
		$note_user_article=recup_note_user($id_article);
		$new_note_user=$note_user_article;
		switch($_GET['note']){
			case 'moins' :
				$new_note_user=-1;
				break ;
			case 'plus' :
				$new_note_user=1;
				break ;
			default :
				header("Location:article.php?article=".$id_article);
		}
	
		update_note_user($id_article, $new_note_user);
		header("Location:article.php?article=".$id_article);
	}
}

//Si on veut supprimer l'article depuis l'URL
if(isset($_GET['supprimer']))
{
	if($statut_menu>3 && $id_user_article!=$id){
		header("Location:actu.php?type=".$type_article);
	}
}

include_once("article.html");
?>