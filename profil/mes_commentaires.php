<?php
include_once("accessoires/fonctions_commentaire.php");
//récupérer les commentaires de l'utilisateur
$res = recup_commentaire_user();
$i = 0;
while ($data = mysqli_fetch_array($res)){
	$id_article_com[$i]=$data['id_article'];
	$title_article_com[$i]=$data['title'];
	$text_com[$i]=$data['text_com'];
	$note_globale[$i]=$data['note_globale'];
	$date_com[$i]=format_date($data['date_com']);
	$id_com[$i]=$data['id_com'];
	$i++;
}
?>