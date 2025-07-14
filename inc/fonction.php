<?php
require("connexion.php");

    function get_membre_connecte($email,$mdp){
        $sql="SELECT * FROM membre WHERE email='%s' AND mot_de_passe='%s'";
        $sql=sprintf($sql,$email,$mdp);
        
        $resultat=mysqli_query(dbconnect(),$sql);
        $donnees=mysqli_fetch_assoc($resultat);

        return $donnees;
    }
    function inserer_membre($nom, $date, $genre, $email, $ville, $mdp) {
        
        $db = dbconnect();

        // Requête préparée pour éviter les injections SQL
        $requet = "INSERT INTO mmembre (nom, date_de_naissance, genre, email, ville, mdp, image_profil) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";

        
        $stmt = mysqli_prepare($db, $requet);

        // Vérification de la préparation
        if (!$stmt) {
            die("Erreur de préparation de la requête : " . mysqli_error($db));
        }

        // Ajout des paramètres (image_profil est laissé vide par défaut)
        $image_profil = ''; // Par défaut, aucune image
        mysqli_stmt_bind_param($stmt, "sssssss", $nom, $date, $genre, $email, $ville, $mdp, $image_profil);

        // Exécution de la requête
        if (!mysqli_stmt_execute($stmt)) {
            die("Erreur lors de l'exécution de la requête : " . mysqli_stmt_error($stmt));
        }

        // Fermeture de la requête
        mysqli_stmt_close($stmt);
    }
?>