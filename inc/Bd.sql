CREATE DATABASE reseau;
USE reseau;
CREATE TABLE membre(
    IdMembre INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(20),
    Dtn DATE,
    Email VARCHAR(30),
    mot_de_passe VARCHAR(30)

);
CREATE TABLE publication(
    Id_membre INT,
    publi VARCHAR(100),
    date_publi DATETIME,
    IdPublication INT PRIMARY KEY AUTO_INCREMENT

);

CREATE TABLE commentaire(
Id_Publi INT,
date_com DATETIME,
com VARCHAR(150),
Id_m INT

);

CREATE TABLE amis (
Id_membre1 INT,
Id_membre2 INT,
DHD DATETIME,
DHA DATETIME
);

INSERT INTO amis(Id_membre1,Id_membre2,DHD,DHA) VALUES (1,2,NOW(),NOW()); 
INSERT INTO membre(Nom,Dtn,Email,mot_de_passe) VALUES("Faneva","2006-01-10","fanevabe@gmail.com","mafyloha");
INSERT INTO membre(Nom,Dtn,Email,mot_de_passe) VALUES("Salomon","2006-04-18","moon@gmail.com","moonlight");

SELECT membre.Nom FROM (
SELECT Id_membre1,Id_membre2 FROM amis WHERE DHA IS NOT NULL AND Id_membre1=2 UNION
SELECT Id_membre2,Id_membre1 FROM amis WHERE DHA IS NOT NULL AND Id_membre2=2) AS namana 
JOIN membre ON membre.IdMembre=namana.Id_membre2 ;

SELECT m.Nom
FROM membre m
WHERE m.IdMembre NOT IN (
    SELECT Id_membre2
    FROM (
        SELECT Id_membre1, Id_membre2 
        FROM amis 
        WHERE DHA IS NOT NULL AND Id_membre1 = 1
        UNION
        SELECT Id_membre2, Id_membre1 
        FROM amis 
        WHERE DHA IS NOT NULL AND Id_membre2 = 1
    ) AS amis_de_2
)
AND m.IdMembre != 1;

ALTER TABLE membre ADD PDP VARCHAR(30);
ALTER TABLE membre CHANGE PDP PDP VARCHAR(100);
UPDATE membre 
SET PDP = '../assets/image/faneva.jpg' 
WHERE IdMembre = 1;
UPDATE membre SET
PDP='../assets/image/salomon.jpg' WHERE IdMembre = 3;
UPDATE amis SET DHD='NULL' WHERE Id_membre1=1 AND Id_membre2=3;
DELETE FROM amis WHERE DHA IS NOT NULL AND Id_membre2=3 AND Id_membre1=1;

INSERT INTO amis (Id_membre1,Id_membre2,DHD) VALUES (3,1,NOW());
INSERT INTO amis (Id_membre1,Id_membre2) VALUES (4,3);
INSERT INTO amis (Id_membre1,Id_membre2,DHD) VALUES (3,4,NOW());

ALTER TABLE commentaire ADD date_com DATETIME ;
DELETE FROM amis WHERE Id_membre1 IS NULL;
INSERT INTO amis (DHA) VALUES (NOW()) WHERE Id_membre1=3 AND Id_membre2=4;
UPDATE TABLE amis SET DHA=NOW() WHERE Id_membre1=3 AND Id_membre2=4;

ALTER TABLE commentaire ADD Id_m INT ;
UPDATE commentaire SET Id_m=1;