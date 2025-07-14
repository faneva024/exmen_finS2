CREATE DATABASE exam;
USE exam;
CREATE TABLE membre(
    id_membre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(20),
    date_de_naissance DATE,
    genre ENUM('M','F','autres'),
    email VARCHAR(30),
    ville VARCHAR(40).
    mdp VARCHAR(30),
    image_profil VARCHAR(50)
);

CREATE TABLE categorie_objet(
    id_categorie INT PRIMARY KEY AUTO_INCREMENT,
    nom_categorie VARCHAR(50)
);

CREATE TABLE objet(
    id_objet INT PRIMARY KEY AUTO_INCREMENT,
    nom_objet VARCHAR(50),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY(id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY(id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE image_objet(
    id_image INT PRIMARY KEY AUTO_INCREMENT,
    id_objet INT,
    nom_image VARCHAR(50),
    FOREIGN KEY(id_objet) REFERENCES objet(id_objet) 
);

CREATE TABLE emprunt(
    id_emprunt INT PRIMARY KEY AUTO_INCREMENT,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY(id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY(id_membre) REFERENCES membre(id_membre)  
);

INSERT INTO membre(nom, date_de_naissance, genre, email, ville, mdp, image_profil) VALUES
('Bob', '1999-01-01', 'M', 'bob@gmail.com', 'Antananarivo', 'bob123', 'bob.jpg'),
('Faneva', '2000-03-03', 'M', 'faneva@gmail.com', 'Antananarivo', 'faneva123', 'faneva.jpg'),
('Fenosoa', '2005-06-19', 'F', 'fenosoaratsiri@gmail.com', 'Antananarivo', 'fenosoa123', 'fenosoa.jpg'),
('Jimmy', '1997-05-05', 'M', 'jimmy@gmail.com', 'Antananarivo', 'jimmy123', 'jimmy.jpg');

INSERT INTO categorie_objet(nom_categorie) VALUES
('Esthetique'),
('Bricolage'),
('Mecanique'),
('Cuisine');

INSERT INTO objet(nom_objet, id_categorie, id_membre) VALUES
('Sèche-cheveux', 1, 1), 
('Tondeuse à barbe', 1, 2), 
('Perceuse', 2, 3), 
('Tournevis électrique', 2, 4),
('Clé à molette', 3, 1), 
('Pompe à vélo', 3, 2),
('Mixeur', 4, 3),
('Machine à café', 4, 4),
('Fer à lisser', 1, 3), 
('Scie circulaire', 2, 4); 

