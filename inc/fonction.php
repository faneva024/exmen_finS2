<?php
require("connection.php");

    function get_membre_connecte($email,$mdp){
        $sql="SELECT * FROM membres WHERE email='%s' AND mot_de_passe='%s'";
        $sql=sprintf($sql,$email,$mdp);
        
        $resultat=mysqli_query(dbconnect(),$sql);
        $donnees=mysqli_fetch_assoc($resultat);

        return $donnees;
    }

?>