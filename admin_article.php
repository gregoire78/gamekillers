<?php
/**
 * Créer par: Grégoire JONCOUR
 * Date: 18/03/2015
 * Projet : gamekillers
 **/
session_start();
include_once("accessoires/menu.php");
if($statut_menu != 3)//administrateur
{
    header("Location:index.php");
}

//On récupère les articles de l'utilisateur
$res = recup_articles_non_valide();
$i=0;
while($data = mysqli_fetch_array($res))
{
    $id_article[$i]=$data['id_article'];
    $titre_article[$i]=$data['title'];
    $texte_article[$i]=$data['texte'];
    $date_article[$i]=format_date($data['date_art']);
    $libelle_article[$i]=$data['libelle'];

    //concernant l'auteur
    $id_auteur_article[$i] = $data['id_user'];
    $pseudo_auteur_article[$i] = $data['pseudo'];
    $avatar_auteur_article[$i] = $data['avatar'];
    $i++;
}


//si on clique sur valider
if(isset($_GET['valider']))
{
    $valider = $_GET['valider'];
    update_article_user_statut_valide($valider);
    header("Location:admin_article.php");
}

if(isset($_GET['supprimer']))
{
    $supprimer = $_GET['supprimer'];
    delete_article($supprimer);
    header("Location:admin_article.php");
}
include_once("admin_article.html");