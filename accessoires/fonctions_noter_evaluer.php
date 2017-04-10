<?php
/////////////////NOTER///////////////////

//Fonction pour mettre à jour une note
function update_note_user($id_article, $note){
	require("connect_bdd.php");//On inclut la connexion à la bdd
	$id=$_SESSION['id_user'];
	$query="UPDATE noter SET note_note='".$note."'
	        WHERE id_article='".$id_article."' AND id_user='".$id."'" ;
	$res= mysqli_query($bdd, $query);
	update_note_article($id_article);
}

//Fonction pour récupérer la note de l'utilisateur d'un article si elle existe
function recup_note_user($id_article){
	require("connect_bdd.php");
	$note_user_article=0;
	if(isset($_SESSION['id_user'])){
		$id=$_SESSION['id_user'];
		$query="SELECT note_note FROM noter
				WHERE id_article='".$id_article."' AND id_user='".$id."'";
		
		$res=mysqli_query($bdd, $query);
		if(mysqli_num_rows($res)==1){
			$data = mysqli_fetch_array($res);
			$note_user_article=$data['note_note'];
		}
	}
	return $note_user_article;
}

//Fonction pour mettre à jour la note totale d'un article
function update_note_article($id_article){
	require("connect_bdd.php");//On inclut la connexion à la bdd
	
	$query="UPDATE article
			SET note_totale=(SELECT SUM(note_note)FROM noter WHERE id_article='".$id_article."')
	        WHERE id_article='".$id_article."'" ;
	$res= mysqli_query($bdd, $query);
}

////////////////////////EVALUER/////////////////////
//Fonction pour evaluer un commentaire
function evaluer_user($id_com, $note){
	require("connect_bdd.php");//On inclut la connexion à la bdd
	$id=$_SESSION['id_user'];
    $query = "INSERT INTO evaluer (id_user, id_com, note_com, date_eval) 
			  VALUE ('".$id."', '".$id_com."', '".$note."', NOW() )";
    $res = mysqli_query($bdd, $query);
	update_note_commentaire($id_com);
}

//Fonction pour mettre à jour une évaluation
function update_evaluer_user($id_com, $note){
	require("connect_bdd.php");//On inclut la connexion à la bdd
	$id=$_SESSION['id_user'];
	$query = "UPDATE evaluer set note_com='".$note."', date_eval= NOW()
			  WHERE id_com=".$id_com." AND id_user=".$id."" ;
    $res = mysqli_query($bdd, $query);	
    
	update_note_commentaire($id_com);
}

//Fonction pour mettre à jour la note globale d'un commentaire
function update_note_commentaire($id_com){
	require("connect_bdd.php");//On inclut la connexion à la bdd
	$query="UPDATE commentaire
			SET note_globale=(SELECT SUM(note_com) FROM evaluer WHERE id_com=".$id_com.")
	        WHERE id_com=".$id_com."" ;
	$res= mysqli_query($bdd, $query);	
}

//Fonction pour récupérer la note du commentaire de l'utilisateur si elle existe
function recup_eval_user($id_com){
	require("connect_bdd.php");
	$note_user_com=0;
	if(isset($_SESSION['id_user'])){
		$id=$_SESSION['id_user'];
		$query="SELECT note_com FROM evaluer
				WHERE id_com=".$id_com." AND id_user=".$id."";
		
		$res=mysqli_query($bdd, $query);
		if(mysqli_num_rows($res)==1){
			$data = mysqli_fetch_array($res);
			$note_user_com=$data['note_com'];
		}
	}
	return $note_user_com;
}
?>