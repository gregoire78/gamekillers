<?php
//On récupère les articles que l'utilisateur à publier
$res = recup_article_user($id,1);
$i=0;
while ($data = mysqli_fetch_array($res)){
	$id_article_user[$i]=$data['id_article'];
	$title_article_user[$i]=$data['title'];
	$date_article_user[$i]=format_date($data['date_art']);
	$libelle_article_user[$i]=$data['libelle'];
	$note_totale_article_user[$i]=$data['note_totale'];
	$i++;
}

//On récupère les articles en attente de validation de l'utilisateur
$res = recup_article_user($id,0);
$i=0;
while ($data = mysqli_fetch_array($res)){
	$id_article_user_non[$i]=$data['id_article'];
	$title_article_user_non[$i]=$data['title'];
	$date_article_user_non[$i]=format_date($data['date_art']);
	$libelle_article_user_non[$i]=$data['libelle'];
	$i++;
}
?>