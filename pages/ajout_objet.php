<?php
session_start();
include('../inc/header.php');
require('../inc/connexion.php');
$bdd = dbconnect();


if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

$sql_categorie = "SELECT * FROM emprunt_categorie_objet";
$resultat_categories = mysqli_query($bdd, $sql_categorie);
?>

<main class="container py-4">
    <section class="card shadow p-4 border-primary">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 text-primary">Ajouter un nouvel objet</h2>
            <a href="accueil.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>

        <form method="post" action="traitement_ajout_objet.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom_objet" class="form-label">Nom de l'objet</label>
                <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
            </div>
            
            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="categorie" name="categorie" required>
                    <option value="">-- Sélectionner une catégorie --</option>
                    <?php while ($cat = mysqli_fetch_assoc($resultat_categories)) { ?>
                        <option value="<?php echo $cat['id_categorie']; ?>">
                            <?php echo $cat['nom_categorie']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="images" class="form-label">Images de l'objet</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                <div class="form-text">Vous pouvez sélectionner plusieurs images. La première sera l'image principale.</div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i> Ajouter l'objet
                </button>
            </div>
        </form>
    </section>
</main>

<?php include('../inc/footer.php'); ?>