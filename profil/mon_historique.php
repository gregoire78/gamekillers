<?php

//récupération des articles lu par l'utilisateur
$res = historique();
$i=0;
while ($data = mysqli_fetch_array($res)){
	$id_article_lu[$i]=$data['id_article'];
	$date_lu[$i] = format_date($data['date_lecture']);
	$type_article_auteur[$i]=$data['libelle'];
	$title_article_lu[$i] = $data['title'];
	$date_art_lu[$i]=format_date($data['date_art']);
	$pseudo_auteur[$i] = $data['pseudo'];
	$avatar_auteur[$i] = $data['avatar'];	
	$i++;
}
?>