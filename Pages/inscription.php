<?php
include('../inc/header.php');
include('../inc/fonction.php');
include('../inc/connexion.php');
?>
<main>
<div class="main">
        <h3>Creer votre compte des maintenant</h3>
        <form action="traitement.php" method="get">
            <p>Nom: <input type="text" name="nom" id="login" placeholder="Entrez votre nom"></p>
            <p>Date de naissance: <input type="date" name="date" id="login" placeholder="Entrez votre date de naissance"></p>
            <P>Genre: <input type="texte" name="genre" id="login" placeholder="M ou F ou autres"></P>
            <p>Email: <input type="email" name="email" id="login" placeholder="Entrez votre Email"></p>
            <p>Ville: <input type="text" name="ville" id="login" placeholder="Votre ville"></p>

            <p>Mot de passe: <input type="password" name="password" id="login" placeholder="Entrez votre mot de passe"></p>
            <input type="submit" value="valider" id="valider">
        </form>
    </div>
</main>

<?php
include('../inc/header.php');
?>