<?php
/**
 * Créer par: Grégoire JONCOUR
 * Date: 03/03/2015
 * Projet : gamekillers
 * Description : script d'inscription
 **/
session_start();

include_once("accessoires/menu.php");
auto_connexion('profil.php',NULL);

if(isset($_POST['inscrire']))
{
    //trim supprime les espaces avant et apres la chaine
    $pseudo_user = real_scape(htmlentities(trim($_POST["pseudo"])));
    $lastname_user = real_scape(htmlentities(ucfirst(trim($_POST["nom"]))));
    $firstname_user = real_scape(htmlentities(ucfirst(trim($_POST["prenom"]))));
    $pw_user = htmlentities($_POST["password"]);
    $conf_pw_user = htmlentities($_POST["conf_password"]);
    $email_user = real_scape(htmlentities(trim($_POST["email"])));
    $string=35;

    /****************verifications pour le pseudo***********************/
    if(empty($pseudo_user))
    {
        $errors[1] = "Veuillez saisir un nom d'utlisateur";
    }
    else
    {
        if(mb_strlen(html_entity_decode($pseudo_user),'utf-8') >= $string)
        {
            $errors[1] = "Votre pseudo ne doit pas dépasser <b>".$string." caractères</b>";
        }
        else
        {
            //vérifions s'il ya des caracteres speciaux dans le champs pseudo
            if(preg_match('/[^0-9A-Za-zàâçéèêëîïôûùüÿñæœ_]/',html_entity_decode($pseudo_user)))
            {
                $errors[1] = "Veuillez n'insérer que des lettres ou chiffres dans votre pseudo.";
            }
            else
            {
                //vérifions si le pseudo éxiste
                if(verif_existe('pseudo',$pseudo_user)!=0)
                {
                    $errors[1] = "Le pseudo <b><i>".$pseudo_user."</i></b> n'est pas disponible";
                }
            }
        }
    }
    /****************************verifions le nom*******************************/
    if(empty($lastname_user))
    {
        $errors[2] = "Veuillez saisir votre nom";
    }
    else
    {
        if(strlen(html_entity_decode($lastname_user)) >= $string)
        {
            $errors[2] = "Votre nom ne doit pas dépasser <b>".$string." caractères</b>";
        }
        else
        {
            if(preg_match('/[^A-Za-zàâçéèêëîïôûùüÿñæœ ]/',html_entity_decode($lastname_user)))
            {
                $errors[2] = "Veuillez n'insérer que des lettres dans votre nom.";
            }
        }
    }
    /*************************vérifions le prenom*******************************/
    if(empty($firstname_user))
    {
        $errors[3] = "Veuillez saisir votre prénom";
    }
    else
    {
        if(strlen(html_entity_decode($firstname_user)) >= $string)
        {
            $errors[3] = "Votre prénom ne doit pas dépasser <b>".$string." caractères</b>";
        }
        else
        {
            if(preg_match('/[^A-Za-zàâçéèêëîïôûùüÿñæœ ]/', html_entity_decode($firstname_user)))
            {
                $errors[3] = "Veuillez n'insérer que des lettres dans votre prénom.";
            }
        }
    }
    /**************************verification mot de passe************************/
    if(empty($pw_user))
    {
        $errors[4] = "Veuillez saisir votre mot de passe";
    }
    else
    {
        if(!preg_match('/[a-z]/',$pw_user) || !preg_match('/[A-Z]/',$pw_user) || !preg_match('/[0-9]/',$pw_user) || strlen($pw_user)<8)
        {
            $errors[4] = "Saisir une Majuscule, un nombre et 8 caractères minium !";
        }
    }
    if(empty($conf_pw_user))
    {
        $errors[5] = "Veuillez confirmer votre mot de passe";
    }
    else
    {
        //verifions si les mots de passes sont identiques
        if(!empty($pw_user) && $pw_user != $conf_pw_user)
        {
            $errors[5] = "Vos deux mots de passe ne sont pas identiques";
        }
    }
    /******************************verifions l'email*****************************/
    if(empty($email_user))
    {
        $errors[6] = "Veuillez saisir votre e-mail";
    }
    else
    {
        //verifions l'email est valide
        if (!filter_var($email_user, FILTER_VALIDATE_EMAIL))
        {
            $errors[6] = "Ceci n'est pas une adresse mail valide";
        }
        else
        {
            //verifions si l'email existe
            if(verif_existe('email',$email_user)!=0)
            {
                $errors[6] = "L'adresse <b><i>".$email_user."</i></b> éxiste déjà";
            }
        }
    }

    /********************si il n'y a aucune erreur(s)********************/
    if(empty($errors))
    {
        $password = password_hash($pw_user,PASSWORD_BCRYPT);
        insertion_user($pseudo_user,$email_user,$firstname_user,$lastname_user,$password);
        $success = true;
        header('Refresh: 5;URL=index.php');
    }
}

include_once("inscription.html");