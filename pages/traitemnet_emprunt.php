<?php
session_start();
include_once 'connexion.php'; // Inclut ton fichier de connexion avec dbconnect()

if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Vous devez être connecté pour emprunter un objet.";
    $_SESSION['message_type'] = "danger";
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_objet = filter_input(INPUT_POST, 'id_objet_a_emprunter', FILTER_VALIDATE_INT);
    $date_retour_prevue_str = filter_input(INPUT_POST, 'date_retour_prevue', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id_emprunteur = $_SESSION['user_id'];
    $date_emprunt = date('Y-m-d'); // Date actuelle

    if (!$id_objet || !$date_retour_prevue_str) {
        $_SESSION['message'] = "Données d'emprunt manquantes ou invalides.";
        $_SESSION['message_type'] = "danger";
        header('Location: accueil.php');
        exit();
    }

    // Validation de la date de retour prévue (doit être future)
    if (strtotime($date_retour_prevue_str) <= strtotime($date_emprunt)) {
        $_SESSION['message'] = "La date de retour prévue doit être postérieure à la date d'emprunt.";
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

    // 1. Vérifier si l'objet existe et est disponible (non déjà emprunté et non possédé par l'emprunteur)
    $query_check = "
        SELECT o.id_objet, o.id_proprietaire
        FROM objets o
        LEFT JOIN emprunts e ON o.id_objet = e.id_objet AND e.date_retour_effective IS NULL
        WHERE o.id_objet = ? AND e.id_emprunt IS NULL
    ";
    if ($stmt_check = mysqli_prepare($bdd, $query_check)) {
        mysqli_stmt_bind_param($stmt_check, "i", $id_objet);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $objet = mysqli_fetch_assoc($result_check);
        mysqli_stmt_close($stmt_check);

        if (!$objet) {
            $_SESSION['message'] = "L'objet n'existe pas ou n'est plus disponible à l'emprunt.";
            $_SESSION['message_type'] = "danger";
            header('Location: accueil.php');
            exit();
        }

        if ($objet['id_proprietaire'] == $id_emprunteur) {
            $_SESSION['message'] = "Vous ne pouvez pas emprunter vos propres objets.";
            $_SESSION['message_type'] = "danger";
            header('Location: accueil.php');
            exit();
        }

        // 2. Insérer l'emprunt dans la table 'emprunts'
        $query_insert = "INSERT INTO emprunts (id_objet, id_emprunteur, date_emprunt, date_retour) VALUES (?, ?, ?, ?)";
        if ($stmt_insert = mysqli_prepare($bdd, $query_insert)) {
            mysqli_stmt_bind_param($stmt_insert, "iiss", $id_objet, $id_emprunteur, $date_emprunt, $date_retour_prevue_str);
            if (mysqli_stmt_execute($stmt_insert)) {
                $_SESSION['message'] = "L'objet a été emprunté avec succès !";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erreur lors de l'enregistrement de l'emprunt : " . mysqli_error($bdd);
                $_SESSION['message_type'] = "danger";
            }
            mysqli_stmt_close($stmt_insert);
        } else {
            $_SESSION['message'] = "Erreur lors de la préparation de la requête d'emprunt : " . mysqli_error($bdd);
            $_SESSION['message_type'] = "danger";
        }

    } else {
        $_SESSION['message'] = "Erreur lors de la préparation de la vérification de l'objet : " . mysqli_error($bdd);
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