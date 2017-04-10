<?php
/**
 * Créer par: Grégoire JONCOUR
 * Projet : gamekillers
 * Description : script du profile
 **/
session_start();
include_once("accessoires/menu.php");

auto_connexion(NULL,'inscription.php');

include_once("profil/mon_profil.php");
include_once("profil/mon_historique.php");
include_once("profil/mes_commentaires.php");
include_once("profil/mes_notes.php");
include_once("profil/mes_articles.php");

include_once("profil.html");
?>