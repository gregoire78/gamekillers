<?php
/**
 * Créer par: Grégoire JONCOUR
 * Date: 17/03/2015
 * Projet : gamekillers
 **/
session_start();
include_once("accessoires/menu.php");
//On vérifie si l'utilisateur est déjà conecter
auto_connexion(NULL,NULL);

if(isset($_GET['membre']))
{
    $id_membre = $_GET['membre'];
    if(isset($id) && $id_membre==$id)
    {
        header('Location:profil.php');
    }
    else
    {
        $data_membre = recup_data_user($id_membre);
        if(empty($data_membre))//le l'id du lien ne correspond à aucun id
        {
            header("Location:profil.php");
        }
        else
        {
            $pseudo_membre = $data_membre['pseudo'];
            $id_statut = $data_membre['statut'];
            $statut_membre = statut_user($id_membre);
            $nom_membre = $data_membre['nom'];
            $prenom_membre = $data_membre['prenom'];
            $avatar_membre = $data_membre['avatar'];

            $data_article = recup_article_user($id_membre,1);
            $num_art = mysqli_num_rows($data_article);
            $i=0;
            while ($data = mysqli_fetch_array($data_article)){
                $id_article_user[$i]=$data['id_article'];
                $title_article_user[$i]=$data['title'];
                $date_article_user[$i]=format_date($data['date_art']);
                $libelle_article_user[$i]=$data['libelle'];
                $note_totale_article_user[$i]=$data['note_totale'];
                $i++;
            }
        }
    }
}
else
{
    header('Location:index.php');
}

include_once("membre.html");