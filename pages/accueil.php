<?php
include('../inc/header.php');

session_start();
require('../inc/fonction.php');
require('../inc/connexion.php');
$bdd = dbconnect();

$nom_user = $_SESSION['nom_user'];

$sql_categorie = "SELECT * FROM emprunt_categorie_objet";
$resultat_categories_1 = mysqli_query($bdd, $sql_categorie); 
$resultat_categories_2 = mysqli_query($bdd, $sql_categorie);

$id_categorie = "";
if (isset($_GET['categorie'])) {
    $id_categorie = $_GET['categorie'];
}

$sql = "SELECT emprunt_objet.*, emprunt_categorie_objet.nom_categorie, emprunt_emprunt.date_retour 
        FROM emprunt_objet
        INNER JOIN emprunt_categorie_objet ON emprunt_objet.id_categorie = emprunt_categorie_objet.id_categorie
        LEFT JOIN emprunt_emprunt ON emprunt_objet.id_objet = emprunt_emprunt.id_objet";

if ($id_categorie !== "") {
    $sql .= " WHERE emprunt_objet.id_categorie = " . $id_categorie;
}

$resultat_objets = mysqli_query($bdd, $sql);
?>

<main class="container py-4">

    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary">Bienvenue, <?php echo $nom_user; ?></h1>
        <a href="../inc/deconnexion.php" class="btn btn-danger shadow-sm">
            <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
        </a>
    </header>

    <section class="card shadow mb-4 p-4 border-primary">
        <h2 class="h5 mb-3 text-success">Choisissez une catégorie pour emprunter :</h2>
        <form method="get" action="emprunter_par_categorie.php">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <select name="categorie" class="form-select form-select-lg border-success" required>
                        <option value="">-- Sélectionner une catégorie --</option>
                        <?php while ($cat = mysqli_fetch_assoc($resultat_categories_2)) { ?>
                            <option value="<?php echo $cat['id_categorie']; ?>">
                                <?php echo $cat['nom_categorie']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-lg w-100 shadow">
                        <i class="bi bi-eye me-2"></i> Voir les objets
                    </button>
                </div>
            </div>
        </form>
    </section>

    <section class="card shadow mb-4 p-4 border-info">
        <h2 class="h5 mb-3 text-info">Filtrer les objets</h2>
        <form method="get" action="accueil.php" class="row g-3">
            <div class="col-md-6">
                <label for="categorie" class="form-label">Par catégorie :</label>
                <select name="categorie" id="categorie" class="form-select border-info" onchange="this.form.submit()">
                    <option value="">-- Toutes les catégories --</option>
                    <?php while ($cat = mysqli_fetch_assoc($resultat_categories_1)) { ?>
                        <option value="<?php echo $cat['id_categorie']; ?>" 
                            <?php if ($id_categorie == $cat['id_categorie']) echo 'selected'; ?>>
                            <?php echo $cat['nom_categorie']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <a href="accueil.php" class="btn btn-outline-info w-100">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </section>

    <section class="card shadow p-4 border-success">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 text-success">Objets disponibles</h2>
            <button class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Nouvel objet
            </button>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php while ($objet = mysqli_fetch_assoc($resultat_objets)) { ?>
                <article class="col">
                    <div class="card h-100 border-<?php echo $objet['date_retour'] ? 'warning' : 'success'; ?>">
                        <div class="card-body">
                            <h3 class="card-title h5 text-truncate"><?php echo $objet['nom_objet']; ?></h3>
                            <p class="card-text">
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-tag me-1"></i> <?php echo $objet['nom_categorie']; ?>
                                </span>
                            </p>
                            <div class="mt-2">
                                <?php if (!$objet['date_retour']): ?>
                                    <button class="btn btn-success btn-sm">
                                        <i class="bi bi-cart-check me-1"></i> Emprunter
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-outline-secondary btn-sm ms-2">
                                    <i class="bi bi-info-circle me-1"></i> Détails
                                </button>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <?php if ($objet['date_retour']): ?>
                                    <span class="text-warning">
                                        <i class="bi bi-clock-history me-1"></i> Emprunté jusqu'au <?php echo $objet['date_retour']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-success">
                                        <i class="bi bi-check-circle me-1"></i> Disponible
                                    </span>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>
    </section>

</main>

<?php include('../inc/footer.php') ?>