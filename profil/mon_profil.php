<?php
//données de l'utilisateur

$id_user = $_SESSION['id_user'];
$data_user = recup_data_user($id_user);
$pseudo_user_data = $data_user['pseudo'];
$nom_user_data = $data_user['nom'];
$prenom_user_data = $data_user['prenom'];
$email_user_data = $data_user['email'];
$avatar_user_data = $data_user['avatar'];
$statut = statut_user($id_user);

if(isset($_POST['modifier_profil']))
{
    $nom_user = real_scape(htmlentities(trim($_POST['nom'])));
    $prenom_user = real_scape(htmlentities(trim($_POST['prenom'])));
    $email_user = real_scape(htmlentities(trim($_POST['email'])));
    $pseudo_user = real_scape(htmlentities(trim($_POST['pseudo'])));

    $string =35;
    /********************le nom**************/
    if(empty($nom_user))
    {
        $errors_profile[1] = "Veuillez saisir un nom";
    }
    else
    {
        if(strlen(html_entity_decode($nom_user)) >= $string)
        {
            $errors_profile[1] = "Le nom ne doit pas dépasser <b>".$string." caractères</b>";
        }
        else
        {
            if(preg_match('/[^A-Za-zàâçéèêëîïôûùüÿñæœ ]/',html_entity_decode($nom_user)))
            {
                $errors_profile[1] = "Veuillez n'insérer que des lettres dans votre nom.";
            }
        }
    }
    /****************le prenom***************/
    if(empty($prenom_user))
    {
        $errors_profile[2] = "Veuillez saisir un prénom";
    }
    else
    {
        if(strlen(html_entity_decode($prenom_user)) >= $string)
        {
            $errors_profile[2] = "Le prénom ne doit pas dépasser <b>".$string." caractères</b>";
        }
        else
        {
            if(preg_match('/[^A-Za-zàâçéèêëîïôûùüÿñæœ ]/',html_entity_decode($prenom_user)))
            {
                $errors_profile[2] = "Veuillez n'insérer que des lettres dans votre prénom.";
            }
        }
    }
    /************email***********************/
    if(empty($email_user))
    {
        $errors_profile[3] = "Veuillez saisir un e-mail";
    }
    else
    {
        //verifions l'email est valide
        if (!filter_var($email_user, FILTER_VALIDATE_EMAIL))
        {
            $errors_profile[3] = "Ceci n'est pas une adresse mail valide";
        }
        else
        {
            //verifions si l'email existe
            if($email_user!=$email_user_data && verif_existe('email',$email_user)!=0)
            {
                $errors_profile[3] = "L'adresse <b><i>".$email_user."</i></b> éxiste déjà";
            }
        }
    }
    /******************pseudo*******************/
    if(empty($pseudo_user))
    {
        $errors_profile[4] = "Veuillez saisir un nom d'utlisateur";
    }
    else
    {
        if(strlen(html_entity_decode($pseudo_user)) >= $string)
        {
            $errors_profile[4] = "Votre pseudo ne doit pas dépasser <b>".$string." caractères</b>";
        }
        else
        {
            //vérifions s'il ya des caracteres speciaux dans le champs pseudo
            if(preg_match('/[^0-9A-Za-zàâçéèêëîïôûùüÿñæœ_]/',html_entity_decode($pseudo_user)))
            {
                $errors_profile[4] = "Veuillez n'insérer que des lettres ou chiffres dans votre pseudo.";
            }
            else
            {
                //vérifions si le pseudo éxiste
                if($pseudo_user!=$pseudo_user_data && verif_existe('pseudo',$pseudo_user)!=0)
                {
                    $errors_profile[4] = "Le pseudo <b><i>".$pseudo_user."</i></b> n'est pas disponible";
                }
            }
        }
    }

    if(empty($errors_profile))
    {
        $success = update_profile($id_user,$nom_user,$prenom_user,$email_user,$pseudo_user);
        header('Refresh: 2;URL=#');
    }
}

if(isset($_POST['modifier_mdp']))
{
    $password_actuel = real_scape(htmlentities($_POST['passwordActuel']));
    $password_new = real_scape(htmlentities($_POST['NewPassword']));
    $password_new_repeat = real_scape(htmlentities($_POST['ConfNewPassword']));
    /*****************mdp***************/
    if(empty($password_actuel))
    {
        $errors_mdp[5] = "Veuillez insérer le mot de passe actuel";
    }
    else
    {
        if(!empty($password_actuel))
        {
            $hash = recup_hash('id_user',$id_user);
            if (!password_verify($password_actuel, $hash))
            {
                $errors_mdp[5] = "Le mot de passe actuel est mauvais";
            }
            else
            {
                if(empty($password_new))
                {
                    $errors_mdp[6] = "Veuillez saisir un nouveau mot de passe";
                }
                else
                {
                    if(!preg_match('/[a-z]/',$password_new) || !preg_match('/[A-Z]/',$password_new) || !preg_match('/[0-9]/',$password_new) || strlen($password_new)<8)
                    {
                        $errors_mdp[6] = "Saisir une Majuscule, un nombre et 8 caractères minimum !";
                    }
                    else
                    {
                        if($password_new != $password_new_repeat)
                        {
                            $errors_mdp[7] = "Les deux mots de passe ne sont pas identiques";
                        }
                        else
                        {
                            $password = password_hash($password_new,PASSWORD_BCRYPT);
                        }
                    }
                }
            }
        }
        else
        {
            $password = $password_actuel;
        }
    }

    if(empty($errors_mdp))
    {
        $success=update_password($id_user,$password_new);
        header('Refresh: 2;URL=#');
    }
}

if(isset($_POST['modifier_avatar']))
{
    $dossier = 'images/avatars/';
    $taille_maxi = 100000;
    $extensions = array('.png', '.gif', '.jpg', '.jpeg');

    //si on upload a partir de nos dossiers
    if(isset($_FILES['AvatarFile']['name']))
    {
        $avatar_file = $_FILES['AvatarFile']['name'];
        $avatar_file_tmp = $_FILES['AvatarFile']['tmp_name'];

        $fichier = basename($avatar_file);
        $taille = filesize($avatar_file_tmp);
        $extension = strrchr($avatar_file, '.');

        if(!empty($avatar_file))
        {
            if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
            {
                $errors_avatar[8] = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
            }
            else
            {
                if($taille>$taille_maxi)
                {
                    $errors_avatar[8] = "L'image est supérieur à <b>100 Ko</b>";
                }
                else
                {
                    $fichier = wd_remove_accents($fichier, $charset='utf-8');
                    $fichier=$id_user.'-'.$fichier;
                    upload_avatar($avatar_file_tmp,$fichier,$dossier);
                    update_avatar($id_user,$dossier.$fichier);
                    header('Location:#');
                }
            }
        }
    }
    else
    {
        if(!empty($_POST['AvatarFileHttp']))
        {
            $avatar_http = real_scape(trim($_POST['AvatarFileHttp']));
            if (!@fopen($avatar_http,"r"))
            {
                $errors_avatar[8] = 'Ceci n\'est pas un lien http';
            }
            else
            {
                update_avatar($id_user,$avatar_http);
                header('Location:#');
            }
        }
    }
}
?>