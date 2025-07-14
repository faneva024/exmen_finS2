<?php
include('../inc/header.php');
require('../inc/connexion.php');
require('../inc/fonction.php');
$bdd = dbconnect();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_membre = $_SESSION['id_user'];


$sql_membre = "SELECT * FROM emprunt_membre WHERE id_membre = ?";
$stmt_membre = mysqli_prepare($bdd, $sql_membre);
mysqli_stmt_bind_param($stmt_membre, "i", $id_membre);
mysqli_stmt_execute($stmt_membre);
$membre = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_membre));


$sql_objets = "SELECT o.*, c.nom_categorie 
               FROM emprunt_objet o
               JOIN emprunt_categorie_objet c ON o.id_categorie = c.id_categorie
               WHERE o.id_membre = ?
               ORDER BY c.nom_categorie, o.nom_objet";
$stmt_objets = mysqli_prepare($bdd, $sql_objets);
mysqli_stmt_bind_param($stmt_objets, "i", $id_membre);
mysqli_stmt_execute($stmt_objets);
$result_objets = mysqli_stmt_get_result($stmt_objets);

$objets_par_categorie = [];
while ($objet = mysqli_fetch_assoc($result_objets)) {
    $objets_par_categorie[$objet['nom_categorie']][] = $objet;
}
?>

<main class="container py-4">
    <section class="card shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 text-primary">Profil de <?php echo $membre['nom']; ?></h2>
            <a href="accueil.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="../uploads/<?php echo $membre['image_profil'] ?: 'default.jpg'; ?>" 
                     class="img-fluid rounded-circle mb-3" 
                     alt="Photo de profil"
                     style="width: 200px; height: 200px; object-fit: cover;">
                <h3 class="h5"><?php echo $membre['nom']; ?></h3>
                <p class="text-muted"><?php echo $membre['ville']; ?></p>
            </div>
            
            <div class="col-md-8">
                <div class="card border-0">
                    <div class="card-body">
                        <h3 class="h5 card-title">Informations personnelles</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Date de naissance:</span>
                                <span><?php echo date('d/m/Y', strtotime($membre['date_naissance'])); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Genre:</span>
                                <span><?php echo $membre['genre']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Email:</span>
                                <span><?php echo $membre['email']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Ville:</span>
                                <span><?php echo $membre['ville']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 text-primary">Mes objets</h2>
            <a href="ajout_objet.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un objet
            </a>
        </div>
        
        <?php if (empty($objets_par_categorie)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore ajouté d'objets à partager.
            </div>
        <?php else: ?>
            <?php foreach ($objets_par_categorie as $categorie => $objets): ?>
                <h3 class="h5 mt-4 border-bottom pb-2"><?php echo $categorie; ?></h3>
                <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
                    <?php foreach ($objets as $objet): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php
                                // Récupérer la première image de l'objet
                                $sql_img = "SELECT nom_image FROM emprunt_images_objet WHERE id_objet = ? LIMIT 1";
                                $stmt_img = mysqli_prepare($bdd, $sql_img);
                                mysqli_stmt_bind_param($stmt_img, "i", $objet['id_objet']);
                                mysqli_stmt_execute($stmt_img);
                                $image = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_img));
                                ?>
                                <img src="../uploads/<?php echo $image['nom_image'] ?? 'default.jpg'; ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo $objet['nom_objet']; ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h4 class="card-title h6"><?php echo $objet['nom_objet']; ?></h4>
                                    <a href="fiche_objet.php?id=<?php echo $objet['id_objet']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<?php include('../inc/footer.php'); ?>