<?php
session_start();
include_once("accessoires/menu.php");
auto_connexion(NULL,'inscription.php');

//Si on veut éditer un article, on récupère les données
if(isset($_GET['article'])){
	$id_article=$_GET['article'];
	$data=mysqli_fetch_array(recup_article($id_article));
	$title_article=$data['title'];
	$text_article=$data['texte'];
	$id_user_article=$data['id_user'];
	$com_allowed=$data['com_allowed'];
	$type_article=$data['id_type'];
	$image_article=$data['image_title'];
	if($statut_menu>3 && $id_user_article!=$id){
		header("Location:index.php");
	}
}
//Si on envoie des données en POST
if(isset($_POST['poster'])||isset($_POST['modifier']))
{
	include_once("accessoires/fonctions_article.php");
	//On recupère les données en poste
	$title_article=real_scape(htmlentities(trim($_POST['title_article'])));
	$text_article=real_scape(trim($_POST['text_article']));
	$libelle2=$_POST['libelle'];
	$article_file = $_FILES['inputArticleFile']['name'];
	$article_file_tmp = $_FILES['inputArticleFile']['tmp_name'];

	//Si on créer un article
	/****verif titre*****/
	$string = 50;
	if(empty($title_article))
	{
		$errors_article[1] = "Veuillez mettre un titre à l'article";
	}
	else
	{
		if(strlen(html_entity_decode($title_article)) >= $string)
		{
			$errors_article[1] = "Le titre ne doit pas dépasser <b>".$string." caractères</b>";
		}
		else
		{
			//vérifions s'il ya des caracteres speciaux
			if(preg_match("/[^0-9A-Za-zàâçéèêëîïôûùüÿñæœ.:!?_\' ]/",$_POST['title_article']))
			{
				$errors_article[1] = "Veuillez n'insérer que des lettres ou chiffres dans le titre.";
			}
		}
	}
	/***verif text**/
	if(empty($text_article))
	{
		$errors_article[2] = "Veuillez remplir l'article";
	}
	/***verif com allow***/
	if(!isset($_POST['com_allowed']))
	{
		$errors_article[3] = "Veuillez choisir une de ces options";
	}
	else
	{
		$com_allowed=$_POST['com_allowed'];
	}
	/***verif image article**/
	if(empty($article_file))
	{
		$errors_article[4] = 'Veuillez ajoutez une image';
	}
	else if(!empty($article_file))
	{
		$dossier = 'images/articles/';
		$taille_maxi = 500000;
		$extensions = array('.png', '.gif', '.jpg', '.jpeg');

		$fichier = basename($article_file);
		$fichier = wd_remove_accents($fichier, $charset='utf-8');
		$taille = filesize($article_file_tmp);
		$extension = strrchr($article_file, '.');

		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
		{
			$errors_article[4] = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
		}
		else
		{
			if($taille>$taille_maxi)
			{
				$errors_article[4] = "L'image est supérieur à <b>500 Ko</b>";
			}
		}
	}
	else
	{
		$fichier = $image_article;
	}

	if(empty($errors_article))
	{
		if(isset($_POST['poster']))
		{
			create_article($title_article, $text_article, $com_allowed, $dossier.$fichier, $libelle2, $statut_menu);
			upload_avatar($article_file_tmp,$fichier,$dossier);
			
		}
		else if(isset($_POST['modifier']))
		{
			//Si on modifie un article
			$id_article=$_GET['article'];
			update_article($title_article, $text_article, $com_allowed, $dossier.$fichier, $libelle2, $id_article, $statut_menu);
			upload_avatar($article_file_tmp,$fichier,$dossier);
		}
		//On redirige vers le type d'actualité
		$id_type=type_article_id($libelle2);
		header("Location:actu.php?type=".$id_type);
	}
}
include_once("edit_article.html");
?>