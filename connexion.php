<?php
session_start();
include_once("accessoires/menu.php");

auto_connexion('profil.php',NULL);

if(isset($_POST['connecter']))
{
    $email_user = real_scape(htmlentities(trim($_POST["email"])));
    $pw_user = real_scape(htmlentities(trim($_POST["password"])));

    //on réupere le hash à partir de l'email
    $hash = recup_hash('email',$email_user);
    //on vérifie le hash avec le mot de pass entrez par l'utilisateur
    if (password_verify($pw_user, $hash))
    {
        $data = connexion_user($email_user,$hash);
        $id_user = $data[0];
        $statut = $data[1];
        if($statut == 0)
        {
            $error_connexion = "Vous êtes Banni !!!";
        }
        else
        {
            $_SESSION['id_user'] = $id_user;
            $id = $_SESSION['id_user'];
            if(isset($_POST['stayco']))
            {
                $crypt_id = sha1(sha1($id_user)."azertyuiopqsdfghjklmwxcvbn,;:!?./§123456789*$^ù&é(-è_çà)=)");
                $crypt_email = sha1(sha1($email_user)."azertyuiopqsdfghjklmwxcvbn,;:!?./§123456789*$^ù&é(-è_çà)=)");
                $expire = time() + 365*24*3600;
                setcookie('id_log',$crypt_id,$expire);
                setcookie('par_log',$crypt_email,$expire);
            }
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
            header('Location: ' . $referer);
        }
    }
    else if(!empty($email_user) || !empty($pw_user))
    {
        $error_connexion = "Email ou mot de passe Incorrecte";
    }
}
include_once("connexion.html");
?>