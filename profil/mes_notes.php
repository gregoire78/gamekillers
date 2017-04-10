<?php
//On récupère les articles que l'utilisateur à noter
$res = recup_article_noter();
$i=0;
while ($data = mysqli_fetch_array($res)){
	$id_article_noter[$i]=$data['id_article'];
	$title_article_noter[$i]=$data['title'];
	$note_article_noter[$i]=$data['note_note'];
	$libelle_article_noter[$i]=$data['libelle'];
	$pseudo_article_auteur_noter[$i]=$data['pseudo'];
	$statut_article_auteur_noter[$i]=statut_user($data['id_user']);
	$avatar_article_auteur_noter[$i]=$data['avatar'];
	$i++;
}


$res = recup_commentaire_noter();
$i=0;
while ($data = mysqli_fetch_array($res)){
	$id_com_noter[$i]=$data['id_com'];
	$id_article_corresp[$i]=$data['id_article'];
	$title_article_corresp[$i]=$data['title'];
	$note_com_noter[$i]=$data['note_com'];
	$text_com_noter[$i]=$data['text_com'];
	$id_user_com_auteur_noter[$i]=$data['id_user'];
	$pseudo_com_auteur_noter[$i]=$data['pseudo'];
	$statut_com_auteur_noter[$i]=statut_user($id_user_com_auteur_noter[$i]);
	$date_com_auteur_noter[$i]=format_date($data['date_com']);
	$avatar_com_auteur_noter[$i]=$data['avatar'];
	$i++;
}
?>