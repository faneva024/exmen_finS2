<?php
require('../inc/fonction.php');
require('../inc/connexion.php');
$bdd = dbconnect();

$nom = $_POST['nom'];
$date_naissance = $_POST['date_naissance'];
$genre = $_POST['genre'];
$email = $_POST['email'];
$ville = $_POST['ville'];
$mdp = $_POST['mdp'];

$image = $_FILES['image_profil']['name'];
$chemin_image = '';

move_uploaded_file($_FILES['image_profil']['tmp_name'], '../assets/uploads/' . $image);
$chemin_image = $image;

$sql = "INSERT INTO emprunt_membre(nom, date_naissance, genre, email, ville, mdp, image_profil) 
        VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
$sql = sprintf($sql, $nom, $date_naissance, $genre, $email, $ville, $mdp, $chemin_image);

mysqli_query($bdd, $sql);
header('Location: accueil.php');
exit();
?>
