<?php
session_start();
include_once 'connexion.php'; // Inclut ton fichier de connexion avec dbconnect()

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Vous devez être connecté pour retourner un objet.";
    $_SESSION['message_type'] = "danger";
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_emprunt = filter_input(INPUT_POST, 'id_emprunt_a_retourner', FILTER_VALIDATE_INT);
    $etat_objet = filter_input(INPUT_POST, 'etat_objet', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $user_id = $_SESSION['user_id'];
    $date_retour_effective = date('Y-m-d H:i:s'); // Date et heure actuelles

    if (!$id_emprunt || empty($etat_objet)) {
        $_SESSION['message'] = "Données de retour manquantes ou invalides.";
        $_SESSION['message_type'] = "danger";
        header('Location: accueil.php');
        exit();
    }

    $bdd = dbconnect(); // Établir la connexion
    if (!$bdd) {
        $_SESSION['message'] = "Erreur de connexion à la base de données.";
        $_SESSION['message_type'] = "danger";
        header('Location: accueil.php');
        exit();
    }

    // 1. Vérifier si l'emprunt existe et appartient bien à l'utilisateur connecté
    // et qu'il n'a pas déjà été retourné.
    $query_check = "
        SELECT id_emprunt
        FROM emprunts
        WHERE id_emprunt = ? AND id_emprunteur = ? AND date_retour_effective IS NULL
    ";
    if ($stmt_check = mysqli_prepare($bdd, $query_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $id_emprunt, $user_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $emprunt = mysqli_fetch_assoc($result_check);
        mysqli_stmt_close($stmt_check);

        if (!$emprunt) {
            $_SESSION['message'] = "Emprunt introuvable ou non valide pour votre compte, ou déjà retourné.";
            $_SESSION['message_type'] = "danger";
            header('Location: accueil.php');
            exit();
        }

        // 2. Mettre à jour l'emprunt avec la date de retour effective et l'état
        $query_update = "UPDATE emprunts SET date_retour_effective = ?, etat_retour = ? WHERE id_emprunt = ?";
        if ($stmt_update = mysqli_prepare($bdd, $query_update)) {
            mysqli_stmt_bind_param($stmt_update, "ssi", $date_retour_effective, $etat_objet, $id_emprunt);
            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['message'] = "L'objet a été retourné avec succès !";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour de l'emprunt : " . mysqli_error($bdd);
                $_SESSION['message_type'] = "danger";
            }
            mysqli_stmt_close($stmt_update);
        } else {
            $_SESSION['message'] = "Erreur lors de la préparation de la requête de retour : " . mysqli_error($bdd);
            $_SESSION['message_type'] = "danger";
        }

    } else {
        $_SESSION['message'] = "Erreur lors de la préparation de la vérification de l'emprunt : " . mysqli_error($bdd);
        $_SESSION['message_type'] = "danger";
    }

    mysqli_close($bdd);
    header('Location: accueil.php');
    exit();

} else {
    $_SESSION['message'] = "Requête invalide.";
    $_SESSION['message_type'] = "danger";
    header('Location: accueil.php');
    exit();
}
?>