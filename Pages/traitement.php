<?php 
    require(".././inc/fonction.php");

    $nom = $_POST['nom'];
    $date = $_POST['date'];
    $genre = $_POST['genre'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $mdp = $_POST['password'];

    inserer_membre($nom, $date, $genre, $email, $ville, $mdp);

    header('Location:index.php');
?>