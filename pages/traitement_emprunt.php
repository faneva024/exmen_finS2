<?php
session_start();
require('../inc/connexion.php');
$bdd = dbconnect();


if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_objet = intval($_POST['id_objet']);
    $date_retour = $_POST['date_retour'];
    $id_membre = $_SESSION['id_user'];
    
 
    $sql = "INSERT INTO emprunt_emprunt (id_objet, id_membre, date_emprunt, date_retour) 
            VALUES (?, ?, CURDATE(), ?)";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $id_objet, $id_membre, $date_retour);
    mysqli_stmt_execute($stmt);
    
    header("Location: fiche_objet.php?id=$id_objet");
    exit;
}
?>