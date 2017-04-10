<?php
/***script de déconnexion*/
session_start();
session_unset();//efface les valeur dans la sesion
session_destroy();//on détruis la sesssion
if(isset($_COOKIE['id_log']))
{
    setcookie('id_log',NULL,-1);
    setcookie('par_log',NULL,-1);
}
//header("location:/" );
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header('Location: ' . $referer);
