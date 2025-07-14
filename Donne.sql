CREATE DATABASE IF NOT EXISTS partage_objets;
USE partage_objets;

-- Table des membres
CREATE TABLE emprunt_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre VARCHAR(10),
    email VARCHAR(100),
    ville VARCHAR(100),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

-- Table des catégories d'objet
CREATE TABLE emprunt_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100)
);

-- Table des objets
CREATE TABLE emprunt_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES emprunt_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES emprunt_membre(id_membre)
);

-- Table des images des objets
CREATE TABLE emprunt_images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES emprunt_objet(id_objet)
);

-- Table des emprunts
CREATE TABLE emprunt_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES emprunt_objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES emprunt_membre(id_membre)
);

INSERT INTO emprunt_membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1995-04-12', 'Femme', 'alice@example.com', 'Antananarivo', 'pass123', 'alice.jpg'),
('Bob', '1990-07-23', 'Homme', 'bob@example.com', 'Toamasina', 'pass123', 'bob.jpg'),
('Charlie', '1988-12-01', 'Homme', 'charlie@example.com', 'Fianarantsoa', 'pass123', 'charlie.jpg'),
('Dina', '2000-03-15', 'Femme', 'dina@example.com', 'Mahajanga', 'pass123', 'dina.jpg');

INSERT INTO emprunt_categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

-- Pour Alice (id_membre = 1)
INSERT INTO emprunt_objet (nom_objet, id_categorie, id_membre) VALUES
('Sèche-cheveux', 1, 1),
('Parfum floral', 1, 1),
('Trousse maquillage', 1, 1),
('Marteau', 2, 1),
('Tournevis', 2, 1),
('Perceuse', 2, 1),
('Crêpière', 4, 1),
('Mixeur', 4, 1),
('Casserole', 4, 1),
('Clé à molette', 3, 1),


('Tournevis plat', 2, 2),
('Perceuse électrique', 2, 2),
('Coffret de douilles', 3, 2),
('Compresseur', 3, 2),
('Lisseur cheveux', 1, 2),
('Fard à paupières', 1, 2),
('Batteur électrique', 4, 2),
('Friteuse', 4, 2),
('Cuiseur vapeur', 4, 2),
('Scie circulaire', 2, 2),

('Clé plate', 3, 3),
('Ponceuse', 2, 3),
('Mascara', 1, 3),
('Lime à ongles', 1, 3),
('Casserole inox', 4, 3),
('Grille-pain', 4, 3),
('Scie sauteuse', 2, 3),
('Perforateur', 2, 3),
('Tondeuse à cheveux', 1, 3),
('Spatule', 4, 3),

('Brosse visage', 1, 4),
('Vernis à ongles', 1, 4),
('Mixeur plongeant', 4, 4),
('Cuit-vapeur', 4, 4),
('Perceuse-visseuse', 2, 4),
('Meuleuse', 2, 4),
('Cric hydraulique', 3, 4),
('Pompe à air', 3, 4),
('Fer à boucler', 1, 4),
('Batteur', 4, 4);

INSERT INTO emprunt_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(5, 2, '2025-07-01', '2025-07-10'),
(12, 1, '2025-07-02', '2025-07-12'),
(20, 3, '2025-07-03', '2025-07-13'),
(25, 4, '2025-07-04', '2025-07-14'),
(30, 1, '2025-07-05', '2025-07-15'),
(6, 3, '2025-07-06', '2025-07-16'),
(18, 4, '2025-07-07', '2025-07-17'),
(33, 2, '2025-07-08', '2025-07-18'),
(36, 1, '2025-07-09', '2025-07-19'),
(39, 3, '2025-07-10', '2025-07-20');
