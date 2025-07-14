<?php
    function verifier_utilisateur($bdd, $email, $mdp){
        $sql="SELECT * FROM emprunt_membre WHERE email='%s' AND mdp ='%s'";
        $sql = sprintf($sql, $email, $mdp);
        $resultat= mysqli_query($bdd, $sql);
        return mysqli_fetch_assoc($resultat);
    }

    function connecter_utilisateur($user)
{
    $_SESSION['nom_user'] = $user['Nom'];
    $_SESSION['id_user'] = $user['id_membre'];
}
?>