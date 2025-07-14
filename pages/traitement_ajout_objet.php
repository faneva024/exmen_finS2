<?php
session_start();
require('../inc/connexion.php');
$bdd = dbconnect();


if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_objet = $_POST['nom_objet'];
    $id_categorie = $_POST['categorie'];
    $id_membre = $_SESSION['id_user'];

   $sql = "INSERT INTO emprunt_objet (nom_objet, id_categorie, id_membre) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($bdd, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $nom_objet, $id_categorie, $id_membre);
    mysqli_stmt_execute($stmt);
    $id_objet = mysqli_insert_id($bdd);

  
    if (!empty($_FILES['images']['name'][0])) {
        $dossier = '../uploads/';
        if (!file_exists($dossier)) {
            mkdir($dossier, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $nom_fichier = basename($_FILES['images']['name'][$key]);
            $chemin = $dossier . $nom_fichier;
            
            if (move_uploaded_file($tmp_name, $chemin)) {
                $sql_img = "INSERT INTO emprunt_images_objet (id_objet, nom_image) VALUES (?, ?)";
                $stmt_img = mysqli_prepare($bdd, $sql_img);
                mysqli_stmt_bind_param($stmt_img, "is", $id_objet, $nom_fichier);
                mysqli_stmt_execute($stmt_img);
            }
        }
    }

    header("Location: fiche_objet.php?id=$id_objet");
    exit;
}
?>