<?php
include('../inc/header.php');
?>
<main class="d-flex align-items-center justify-content-center min-vh-100 py-4">
    <section class="w-100" style="max-width: 500px;">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Créer un compte</h2>

            <form method="post" action="traitement2.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>

                <div class="mb-3">
                    <label for="date_naissance" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                </div>

                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <select class="form-select" id="genre" name="genre">
                        <option>Homme</option>
                        <option>Femme</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" class="form-control" id="ville" name="ville" required>
                </div>

                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" required>
                </div>

                <div class="mb-3">
                    <label for="image_profil" class="form-label">Image de profil</label>
                    <input type="file" class="form-control" id="image_profil" name="image_profil">
                </div>

                <button type="submit" class="btn btn-success w-100">S'inscrire</button>
            </form>

            <footer class="mt-3 text-center">
                <p class="mb-0">Déjà inscrit ? 
                    <a href="login.php">Se connecter</a>
                </p>
            </footer>
        </div>
    </section>
</main>
<?php include('../inc/footer.php'); ?>