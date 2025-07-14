<?php
include('../inc/header.php');
require('../inc/connexion.php');
$bdd = dbconnect();

if (!isset($_GET['id'])) {
    header('Location: accueil.php');
    exit;
}

$id_objet = intval($_GET['id']);

$sql_objet = "SELECT o.*, c.nom_categorie, m.nom AS nom_proprietaire
              FROM emprunt_objet o
              JOIN emprunt_categorie_objet c ON o.id_categorie = c.id_categorie
              JOIN emprunt_membre m ON o.id_membre = m.id_membre
              WHERE o.id_objet = ?";
$stmt_objet = mysqli_prepare($bdd, $sql_objet);
mysqli_stmt_bind_param($stmt_objet, "i", $id_objet);
mysqli_stmt_execute($stmt_objet);
$objet = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_objet));

$sql_images = "SELECT * FROM emprunt_images_objet WHERE id_objet = ?";
$stmt_images = mysqli_prepare($bdd, $sql_images);
mysqli_stmt_bind_param($stmt_images, "i", $id_objet);
mysqli_stmt_execute($stmt_images);
$images = mysqli_fetch_all(mysqli_stmt_get_result($stmt_images), MYSQLI_ASSOC);


$sql_emprunts = "SELECT e.*, m.nom AS nom_emprunteur
                 FROM emprunt_emprunt e
                 JOIN emprunt_membre m ON e.id_membre = m.id_membre
                 WHERE e.id_objet = ?
                 ORDER BY e.date_emprunt DESC";
$stmt_emprunts = mysqli_prepare($bdd, $sql_emprunts);
mysqli_stmt_bind_param($stmt_emprunts, "i", $id_objet);
mysqli_stmt_execute($stmt_emprunts);
$emprunts = mysqli_fetch_all(mysqli_stmt_get_result($stmt_emprunts), MYSQLI_ASSOC);
?>

<main class="container py-4">
    <section class="card shadow p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 text-primary"><?php echo $objet['nom_objet']; ?></h2>
            <a href="accueil.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
        
        <div class="row">
            <div class="col-md-6">
            
                <div id="carouselObjet" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (count($images) > 0): ?>
                            <?php foreach ($images as $index => $image): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="../uploads/<?php echo $image['nom_image']; ?>" class="d-block w-100 rounded" alt="Image de l'objet" style="height: 400px; object-fit: cover;">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img src="../assets/img/default.jpg" class="d-block w-100 rounded" alt="Image par défaut" style="height: 400px; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselObjet" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselObjet" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>
                
                <?php if ($_SESSION['id_user'] == $objet['id_membre']): ?>
                    <div class="mt-3">
                        <form action="supprimer_image.php" method="post">
                            <input type="hidden" name="id_objet" value="<?php echo $id_objet; ?>">
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($images as $image): ?>
                                    <div class="d-flex align-items-center bg-light p-2 rounded">
                                        <input type="checkbox" name="images[]" value="<?php echo $image['id_image']; ?>" class="me-2">
                                        <img src="../uploads/<?php echo $image['nom_image']; ?>" width="50" height="50" class="img-thumbnail me-2">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="submit" class="btn btn-danger mt-2">
                                <i class="bi bi-trash me-1"></i> Supprimer les images sélectionnées
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <h3 class="h5 card-title">Détails de l'objet</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Catégorie:</span>
                                <span><?php echo $objet['nom_categorie']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Propriétaire:</span>
                                <span><?php echo $objet['nom_proprietaire']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Disponibilité:</span>
                                <span class="badge bg-<?php echo empty($emprunts[0]['date_retour']) ? 'success' : 'warning'; ?>">
                                    <?php echo empty($emprunts[0]['date_retour']) ? 'Disponible' : 'Emprunté'; ?>
                                </span>
                            </li>
                        </ul>
                        
                        <?php if (empty($emprunts[0]['date_retour']) && $_SESSION['id_user'] != $objet['id_membre']): ?>
                            <div class="mt-4">
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalEmprunt">
                                    <i class="bi bi-cart-check me-1"></i> Emprunter cet objet
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="card shadow p-4">
        <h3 class="h5 mb-4">Historique des emprunts</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Emprunteur</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($emprunts) > 0): ?>
                        <?php foreach ($emprunts as $emprunt): ?>
                            <tr>
                                <td><?php echo $emprunt['nom_emprunteur']; ?></td>
                                <td><?php echo $emprunt['date_emprunt']; ?></td>
                                <td><?php echo $emprunt['date_retour'] ?? 'En cours'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucun emprunt enregistré pour cet objet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>


<div class="modal fade" id="modalEmprunt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Emprunter "<?php echo $objet['nom_objet']; ?>"</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="traitement_emprunt.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_objet" value="<?php echo $id_objet; ?>">
                    <div class="mb-3">
                        <label for="date_retour" class="form-label">Date de retour prévue</label>
                        <input type="date" class="form-control" id="date_retour" name="date_retour" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer l'emprunt</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../inc/footer.php'); ?>