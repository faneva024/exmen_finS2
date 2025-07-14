<?php
require("connexion.php");

    function get_membre_connecte($email,$mdp){
        $sql="SELECT * FROM membre WHERE email='%s' AND mot_de_passe='%s'";
        $sql=sprintf($sql,$email,$mdp);
        
        $resultat=mysqli_query(dbconnect(),$sql);
        $donnees=mysqli_fetch_assoc($resultat);

        return $donnees;
    }
    function inserer_membre( $nom,$date,$genre,$email,$ville,$mdp){
        $requet="INSERT INTO mmembre(nom, date_de_naissance, genre, email, ville, mdp, image_profil) VALUES ('%s','%s','%s','%s','%s','%s')";
        $requet=sprintf($requet,$nom,$date,$genre,$email,$ville,$mdp);

        mysqli_query(dbconnect(),$requet);
    }
?>