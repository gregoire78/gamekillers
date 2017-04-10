<?php
session_start();
include_once("accessoires/menu.php");
include_once("accessoires/fonction_tag_substr.php");

auto_connexion(NULL,NULL);
$res=recup_article_accueil();
$i=1;
while( ($data=mysqli_fetch_array($res)) && $i<=9){
	$title[$i]=$data['title'];
	$texte[$i]=substr(strip_tags($data['texte']),0,700);
	$image_article[$i]=$data['image_title'];
	$id_article[$i]=$data['id_article'];
	$i++;
}
include_once("index.html");
?>