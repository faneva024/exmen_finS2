<?php 
    require(".././inc/fonctions.php");

    $nom=$_GET['nom'];
    
    $date=$_GET['date'];

    $genre=$_GET['genre'];

    $email=$_GET['email'];

    $ville=$_GET['ville'];
    
    $mdp=$_GET['password'];

    inserer_membre($nom,$date,$email,$mdp);

    header('Location:index.php');
?>