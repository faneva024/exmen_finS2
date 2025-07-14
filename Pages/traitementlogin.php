<?php 
    session_start();
    require(".././inc/fonction.php");
    $email=$_GET['Email'];
    $mdp=$_GET['pass'];

    $donnees=get_membre_connecte($email,$mdp);

    if(isset($donnees)){
        $_SESSION['membre']=$donnees;
        header('Location:accueil.php');
    }

    else if(!isset($donnees)){
        header('Location:index.php?a=1');
    }
?>