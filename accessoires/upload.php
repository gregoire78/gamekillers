<?php
/**
 * Créer par: Grégoire JONCOUR
 * Date: 15/03/2015
 * Projet : gamekillers
 **/
function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    $str = str_replace(' ', '_', $str);
    $str = str_replace('\'', '', $str);
    return $str;
}
$dossier = '../images/articles/';
$pathUrl = '/images/articles/';
$taille_maxi = 200000;
$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.JPG');
$file = $_FILES['upload']['name'];
$file_tmp = $_FILES['upload']['tmp_name'];

$fichier = basename($file);
$fichier = wd_remove_accents($fichier, $charset='utf-8');
$taille = filesize($file_tmp);
$extension = strrchr($file, '.');
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
    $message = "Vous devez envoyer un fichier de type png, gif, jpg, jpeg";
    echo "<script type='text/javascript'>
             window.parent.CKEDITOR.tools.callFunction(1, '', '".$message."');
           </script>";
}
else
{
    $message = "Le fichier ".addcslashes($file,"'")." à été envoyé avec succes";
    echo "<script type='text/javascript'>
             window.parent.CKEDITOR.tools.callFunction(1, '".$pathUrl.$fichier."', '".$message."');
             window.close();
           </script>";
    move_uploaded_file($file_tmp, $dossier . $fichier);
}

?>