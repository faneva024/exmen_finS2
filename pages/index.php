<?php
session_start();
include('../inc/header.php');
?>
<main class="d-flex align-items-center justify-content-center min-vh-100 py-4">
    <section class="w-100" style="max-width: 400px;">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4">Connexion</h2>
            
            <form method="post" action="traitement1.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <footer class="mt-3 text-center">
                <p class="mb-0">Pas encore inscrit ? 
                    <a href="inscription.php">Créer un compte</a>
                </p>
            </footer>
        </div>
    </section>
</main>

<?php
include('../inc/footer.php');
?>