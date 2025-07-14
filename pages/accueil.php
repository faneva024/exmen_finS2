<?php
session_start();
include_once 'connexion.php'; // Inclut ton fichier de connexion avec dbconnect()

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Assure-toi que ce chemin est correct pour ta page de connexion
    exit();
}

$user_id = $_SESSION['user_id'];

// Établir la connexion à la base de données avec ta fonction
$bdd = dbconnect();

// --- Récupération des objets disponibles à l'emprunt ---
$objets_disponibles = [];
if ($bdd) {
    $query_objets = "
        SELECT o.id_objet, o.nom_objet, o.description, o.nom_image, u.pseudo AS proprietaire_pseudo
        FROM objets o
        JOIN utilisateurs u ON o.id_proprietaire = u.id_utilisateur
        LEFT JOIN emprunts e ON o.id_objet = e.id_objet AND e.date_retour_effective IS NULL
        WHERE o.id_proprietaire != ? AND e.id_objet IS NULL
        ORDER BY o.date_ajout DESC
    ";
    if ($stmt_objets = mysqli_prepare($bdd, $query_objets)) {
        mysqli_stmt_bind_param($stmt_objets, "i", $user_id);
        mysqli_stmt_execute($stmt_objets);
        $result_objets = mysqli_stmt_get_result($stmt_objets);
        while ($row = mysqli_fetch_assoc($result_objets)) {
            $objets_disponibles[] = $row;
        }
        mysqli_stmt_close($stmt_objets);
    } else {
        $_SESSION['message'] = "Erreur lors de la préparation de la requête des objets disponibles: " . mysqli_error($bdd);
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Erreur de connexion à la base de données.";
    $_SESSION['message_type'] = "danger";
}


// --- Récupération des emprunts en cours de l'utilisateur connecté ---
$emprunts_en_cours = [];
if ($bdd) {
    $query_emprunts = "
        SELECT e.id_emprunt, o.nom_objet, o.nom_image, e.date_emprunt, e.date_retour, u.pseudo AS proprietaire_pseudo
        FROM emprunts e
        JOIN objets o ON e.id_objet = o.id_objet
        JOIN utilisateurs u ON o.id_proprietaire = u.id_utilisateur
        WHERE e.id_emprunteur = ? AND e.date_retour_effective IS NULL
        ORDER BY e.date_retour ASC
    ";
    if ($stmt_emprunts = mysqli_prepare($bdd, $query_emprunts)) {
        mysqli_stmt_bind_param($stmt_emprunts, "i", $user_id);
        mysqli_stmt_execute($stmt_emprunts);
        $result_emprunts = mysqli_stmt_get_result($stmt_emprunts);
        while ($row = mysqli_fetch_assoc($result_emprunts)) {
            $emprunts_en_cours[] = $row;
        }
        mysqli_stmt_close($stmt_emprunts);
    } else {
        $_SESSION['message'] = "Erreur lors de la préparation de la requête des emprunts en cours: " . mysqli_error($bdd);
        $_SESSION['message_type'] = "danger";
    }
}


?>

<?php include_once 'header.php'; // Inclut ton header existant ?>

<main class="container mt-4">
    <h1 class="mb-4">Bienvenue sur ShareSphere !</h1>

    <?php
    // Affichage des messages de succès ou d'erreur
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">' . $_SESSION['message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

    <section class="card shadow p-4 border-primary mb-4">
        <h2 class="h4 text-primary">Objets disponibles à l'emprunt</h2>
        <?php if (empty($objets_disponibles)): ?>
            <div class="alert alert-info">
                Aucun objet n'est actuellement disponible à l'emprunt. Revenez plus tard !
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($objets_disponibles as $objet): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="uploads/<?php echo htmlspecialchars($objet['nom_image'] ?? 'default.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($objet['nom_objet']); ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($objet['nom_objet']); ?></h5>
                                <p class="card-text flex-grow-1"><?php echo nl2br(htmlspecialchars($objet['description'])); ?></p>
                                <p class="card-text"><small class="text-muted">Proposé par: <strong><?php echo htmlspecialchars($objet['proprietaire_pseudo']); ?></strong></small></p>
                                <div class="mt-auto">
                                    <button type="button" class="btn btn-success w-100"
                                            data-bs-toggle="modal" data-bs-target="#modalEmprunterObjet"
                                            data-id-objet="<?php echo $objet['id_objet']; ?>"
                                            data-nom-objet="<?php echo htmlspecialchars($objet['nom_objet']); ?>"
                                            data-proprietaire="<?php echo htmlspecialchars($objet['proprietaire_pseudo']); ?>">
                                        <i class="bi bi-hand-thumbs-up me-1"></i> Emprunter cet objet
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="card shadow p-4 border-info mb-4">
        <h2 class="h4 text-info">Mes emprunts en cours</h2>
        <?php if (empty($emprunts_en_cours)): ?>
            <div class="alert alert-info">
                Vous n'avez aucun emprunt en cours pour le moment.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($emprunts_en_cours as $emprunt): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="uploads/<?php echo htmlspecialchars($emprunt['nom_image'] ?? 'default.jpg'); ?>"
                                         class="img-fluid rounded-start h-100"
                                         alt="<?php echo htmlspecialchars($emprunt['nom_objet']); ?>"
                                         style="object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="card-title"><?php echo htmlspecialchars($emprunt['nom_objet']); ?></h5>
                                            <p class="card-text mb-1">Emprunté le: <strong><?php echo date('d/m/Y', strtotime($emprunt['date_emprunt'])); ?></strong></p>
                                            <p class="card-text">Retour prévu le: <strong><?php echo date('d/m/Y', strtotime($emprunt['date_retour'])); ?></strong></p>
                                            <p class="card-text"><small class="text-muted">Propriétaire: <strong><?php echo htmlspecialchars($emprunt['proprietaire_pseudo']); ?></strong></small></p>
                                        </div>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-warning w-100"
                                                    data-bs-toggle="modal" data-bs-target="#modalRetourObjet"
                                                    data-id-emprunt="<?php echo $emprunt['id_emprunt']; ?>"
                                                    data-nom-objet="<?php echo htmlspecialchars($emprunt['nom_objet']); ?>">
                                                <i class="bi bi-arrow-return-left me-1"></i> Retourner
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>


    <div class="modal fade" id="modalEmprunterObjet" tabindex="-1" aria-labelledby="modalEmprunterObjetLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalEmprunterObjetLabel">Emprunter un objet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="traitement_emprunt.php" method="POST">
                    <div class="modal-body">
                        <p>Vous êtes sur le point d'emprunter <strong id="empruntNomObjet"></strong> proposé par <strong id="empruntProprietaire"></strong>.</p>
                        <input type="hidden" name="id_objet_a_emprunter" id="idObjetAEmprunter">
                        <div class="mb-3">
                            <label for="dateRetourPrevue" class="form-label">Date de retour prévue :</label>
                            <input type="date" class="form-control" id="dateRetourPrevue" name="date_retour_prevue" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Confirmer l'emprunt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRetourObjet" tabindex="-1" aria-labelledby="modalRetourObjetLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalRetourObjetLabel">Retourner un objet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="traitement_retour.php" method="POST">
                    <div class="modal-body">
                        <p>Confirmez-vous le retour de l'objet : <strong id="retourNomObjet"></strong> ?</p>
                        <input type="hidden" name="id_emprunt_a_retourner" id="idEmpruntARetourner">
                        <div class="mb-3">
                            <label for="etatObjet" class="form-label">État de l'objet à son retour :</label>
                            <select class="form-select" id="etatObjet" name="etat_objet" required>
                                <option value="">Sélectionnez un état</option>
                                <option value="Comme neuf">Comme neuf</option>
                                <option value="Bon état">Bon état</option>
                                <option value="Usure normale">Usure normale</option>
                                <option value="Légèrement endommagé">Légèrement endommagé</option>
                                <option value="Endommagé">Endommagé</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">Confirmer le retour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>
<script>
    // Script pour passer les données à la modale d'emprunt
    var modalEmprunterObjet = document.getElementById('modalEmprunterObjet');
    if (modalEmprunterObjet) {
        modalEmprunterObjet.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Bouton qui a déclenché le modal
            var idObjet = button.getAttribute('data-id-objet');
            var nomObjet = button.getAttribute('data-nom-objet');
            var proprietaire = button.getAttribute('data-proprietaire');

            var modalBodyInputIdObjet = modalEmprunterObjet.querySelector('#idObjetAEmprunter');
            var modalBodyEmpruntNomObjet = modalEmprunterObjet.querySelector('#empruntNomObjet');
            var modalBodyEmpruntProprietaire = modalEmprunterObjet.querySelector('#empruntProprietaire');

            modalBodyInputIdObjet.value = idObjet;
            modalBodyEmpruntNomObjet.textContent = nomObjet;
            modalBodyEmpruntProprietaire.textContent = proprietaire;

            // Pré-remplir la date de retour prévue au lendemain au minimum
            var today = new Date();
            today.setDate(today.getDate() + 1); // Minimum demain
            var tomorrow = today.toISOString().split('T')[0];
            document.getElementById('dateRetourPrevue').setAttribute('min', tomorrow);
            document.getElementById('dateRetourPrevue').value = tomorrow; // Peut être modifié pour une date par défaut plus lointaine
        });
    }

    // Script pour passer les données à la modale de retour
    var modalRetourObjet = document.getElementById('modalRetourObjet');
    if (modalRetourObjet) {
        modalRetourObjet.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Bouton qui a déclenché le modal
            var idEmprunt = button.getAttribute('data-id-emprunt');
            var nomObjet = button.getAttribute('data-nom-objet');

            var modalBodyInputIdEmprunt = modalRetourObjet.querySelector('#idEmpruntARetourner');
            var modalBodyNomObjet = modalRetourObjet.querySelector('#retourNomObjet');

            modalBodyInputIdEmprunt.value = idEmprunt;
            modalBodyNomObjet.textContent = nomObjet;
        });
    }
</script>

</body>
</html>