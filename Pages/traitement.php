<?php 
    require(".././inc/fonction.php");

    $nom=$_GET['nom'];
    
    $date=$_GET['date'];

    $genre=$_GET['genre'];

    $email=$_GET['email'];

    $ville=$_GET['ville'];
    
    $mdp=$_GET['password'];

    inserer_membre($nom,$date,$genre,$email,$ville,$mdp);

    header('Location:index.php');
?>