<?php
include('../inc/header.php');
include('../inc/fonction.php');
include('../inc/connexion.php');
?>
<main>
<div class="main">
        <h3>Veuillez-vous connecter a votre compte</h3>

        <form action="traitementlogin.php" method="get">
            <p>Email: <input type="email" name="Email" id="login" placeholder="Entrez votre Email"></p>
            <p>Mot de passe: <input type="password" name="pass" id="login" placeholder="Entrez votre mot de passe"></p>
            <?php  if(isset($_GET['a'])){?>
                <h4>Email ou mot de passe non valide !!</h4>
            <?php }?>
            <p><input type="submit" value="Se connecter" id="valider"></p>
        </form>
        <a href="inscription.php" id="retour">Pas encore de compte?</a>
    </div>
</main>
   
<?php
include('../inc/footer.php');
?>