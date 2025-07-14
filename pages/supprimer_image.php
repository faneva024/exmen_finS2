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
    
    $sql = "SELECT id_membre FROM emprunt_objet WHERE id_objet = ?";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_objet);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    
    if ($result['id_membre'] != $_SESSION['id_user']) {
        die("Vous n'avez pas le droit de supprimer ces images.");
    }
    
   
    if (!empty($_POST['images'])) {
        foreach ($_POST['images'] as $id_image) {
         
            $sql_img = "SELECT nom_image FROM emprunt_images_objet WHERE id_image = ?";
            $stmt_img = mysqli_prepare($bdd, $sql_img);
            mysqli_stmt_bind_param($stmt_img, "i", $id_image);
            mysqli_stmt_execute($stmt_img);
            $image = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_img));
            
          
            $chemin = "../uploads/" . $image['nom_image'];
            if (file_exists($chemin)) {
                unlink($chemin);
            }
      
            $sql_delete = "DELETE FROM emprunt_images_objet WHERE id_image = ?";
            $stmt_delete = mysqli_prepare($bdd, $sql_delete);
            mysqli_stmt_bind_param($stmt_delete, "i", $id_image);
            mysqli_stmt_execute($stmt_delete);
        }
    }
    
    header("Location: fiche_objet.php?id=$id_objet");
    exit;
}
?>