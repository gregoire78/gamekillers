<?php
/**
 * Créer par: Grégoire JONCOUR
 * Date: 02/03/2015
 * Projet : gamekillers
 * Description : script comptenant les fonctions relatifs à l'utilisateur
 **/
function verif_existe($col,$string)
{
    require("connect_bdd.php");//on inclut la connection à la bdd
    $query = "SELECT id_user FROM users WHERE ".$col."= '".$string."'";
    $res = mysqli_query($bdd, $query);
    $row=mysqli_num_rows($res);
    return $row;
}
//fonction insérant l'utilisateur dans la bdd
function insertion_user($pseudo,$email,$prenom,$nom,$password)
{
    require("connect_bdd.php");//on inclut la connection à la bdd
    $query = "INSERT INTO users(nom,prenom,email,pseudo,password)
              VALUES ('".$nom."','".$prenom."','".$email."','".$pseudo."','".$password."')";
    mysqli_query($bdd,$query);
}
//fonction update le profil (nom,pseudo,email,..)
function update_profile($id_user,$nom,$prenom,$email,$pseudo)
{
    require("connect_bdd.php");
    $query = "UPDATE users SET nom='".$nom."', prenom='".$prenom."', email='".$email."', pseudo='".$pseudo."' WHERE id_user='".$id_user."'";
    mysqli_query($bdd,$query);
    return true;
}
//fonction update le password
function update_password($id_user,$password)
{
    require("connect_bdd.php");
    $password = password_hash($password,PASSWORD_BCRYPT);
    $query = "UPDATE users SET password='".$password."' WHERE id_user='".$id_user."'";
    mysqli_query($bdd,$query);
    return true;
}
//fonction update l'avatar
function update_avatar($id_user,$avatar)
{
    require("connect_bdd.php");
    $query = "UPDATE users SET avatar='".$avatar."' WHERE id_user='".$id_user."'";
    mysqli_query($bdd,$query);
    return true;
}
//fonction pour formater nom du fichier avatar
function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

    return $str;
}
//fonction upload avatar
function upload_avatar($avatar_file_tmp,$avatar_file,$dossier)
{
    move_uploaded_file($avatar_file_tmp, $dossier . $avatar_file);
}
//fonction real string
function real_scape($string)
{
    require("connect_bdd.php");
    $string = mysqli_real_escape_string($bdd,$string);
    return $string;
}

//fonction récupérant et verifie le mot de passe suivant l'email
function recup_hash($col,$email)
{
    require("connect_bdd.php");
    $query = "SELECT password FROM users WHERE ".$col."='".$email."'; ";
    $res = mysqli_query($bdd,$query);
    $data = mysqli_fetch_array($res);
    $password = $data['password'];
    return $password;
}
//fonction récupérant les informations de l'urilisateur
function recup_data_user($id_user)
{
    require("connect_bdd.php");
    $query = "SELECT pseudo,nom,prenom,email,avatar,statut FROM users WHERE id_user='".$id_user."'";
    $res = mysqli_query($bdd,$query);
    $data = mysqli_fetch_array($res);
    return $data;
}
//fonction qui récupère l'id de l'user pour la connexion
function connexion_user($email,$password)
{
    require("connect_bdd.php");
    $query = "SELECT id_user,statut FROM users WHERE email='".$email."' AND password='".$password."'";
    $res = mysqli_query($bdd,$query);
    $res = mysqli_fetch_array($res);
    $id = $res['id_user'];
    $statut = $res['statut'];
    return $data = array($id,$statut);
}
//fonction parmettant d'auto connecter l'utilisateur à inserer sur toute les pages en relation avec l'utilisateur
function auto_connexion($page_redirection_ok,$page_redirection_nok)
{
    require("connect_bdd.php");
    if(isset($_SESSION['id_user']))
    {
        $id_user = $_SESSION['id_user'];
        $query = "SELECT id_user FROM users WHERE id_user= '".$id_user."' AND statut!=0";
        $res = mysqli_query($bdd,$query);
        $data = mysqli_num_rows($res);
        if($data==1)
        {
            if(isset($page_redirection_ok))
            {
                header('Location:'.$page_redirection_ok.'');
                return true;
            }else{return true;}

        }
        else
        {
            header('Location:deconnexion.php');
            return false;
        }
    }
    else if(isset($_COOKIE['id_log']) && isset($_COOKIE['par_log']))
    {
        $i=1;
        $cookie_id = $_COOKIE['id_log'];
        $cookie_email = $_COOKIE['par_log'];
        $query = "SELECT id_user,email FROM users WHERE statut!=0";
        $res = mysqli_query($bdd,$query);
        while($verif=mysqli_fetch_array($res))
        {
            $mail[$i] = $verif['email'];
            $id_user[$i] = $verif['id_user'];
            $crypt_email[$i] = sha1(sha1($mail[$i])."azertyuiopqsdfghjklmwxcvbn,;:!?./§123456789*$^ù&é(-è_çà)=)");
            $crypt_id[$i] = sha1(sha1($id_user[$i])."azertyuiopqsdfghjklmwxcvbn,;:!?./§123456789*$^ù&é(-è_çà)=)");
            if($cookie_email == $crypt_email[$i] &&$cookie_id == $crypt_id[$i])
            {
                $_SESSION['id_user']=$id_user[$i];
                if(isset($page_redirection_ok))
                {
                    header('Location:'.$page_redirection_ok.'');
                    return true;
                }
                else{return true;}
            }
            $i++;
        }
    }
    else
    {
        if(isset($page_redirection_nok))
        {
            header('Location:'.$page_redirection_nok.'');
            return true;
        }else{return true;}
    }
}
//fonction affichant le statutr en fonction du numero
function statut_user($id_user)
{
    $res = recup_data_user($id_user);
    $statut_num = $res['statut'];
    switch ($statut_num)
    {
		case 0 :
			$statut = 'Banni';
			break;
        case 1 :
            $statut = 'Utilisateur';
            break;
        case 2 :
            $statut = 'Rédacteur';
            break;
        case 3 :
            $statut = 'Administrateur';
            break;
        default :
            $statut = 'Utilisateur';
            break;
    }
    return $statut;
}



//Fonction pour donner des droits au utilisateur
function update_statut_user($statut, $id_user){
	require("connect_bdd.php");
	$query="UPDATE users
			SET statut = ".$statut."
	        WHERE id_user=".$id_user."" ;
	$res= mysqli_query($bdd, $query);
}

//Fonction pour récupérer toutes les données de l'utilisateur (sauf le mot de passe)
function recup_all_user(){
	require("connect_bdd.php");
	$query="SELECT id_user, nom, prenom, email, pseudo, avatar, statut
			FROM users
			ORDER BY statut DESC";
	$res=mysqli_query($bdd, $query);
	return $res;
}