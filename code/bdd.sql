-- Table des coordonnées (adresse = identifiant ici)
CREATE TABLE coordonnee (
   id INT PRIMARY KEY,
   pays VARCHAR(50),
   ville VARCHAR(50)
);

-- Table des noms de cadeaux
CREATE TABLE nom_cadeau (
   id INT PRIMARY KEY,
   nom VARCHAR(50),
   type VARCHAR(50)
);

-- Table des enfants (adresse est une clé étrangère vers coordonnee.id)
CREATE TABLE enfant (
   id INT PRIMARY KEY,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   adresse INT NOT NULL,
   FOREIGN KEY (adresse) REFERENCES coordonnee(id)
);

-- Table des cadeaux attribués aux enfants
CREATE TABLE cadeau (
   enfant_id INT,
   cadeau_id INT,
   PRIMARY KEY(enfant_id, cadeau_id),
   FOREIGN KEY (enfant_id) REFERENCES enfant(id),
   FOREIGN KEY (cadeau_id) REFERENCES nom_cadeau(id)
);

-- Table des enfants gentils (réutilise la clé composée de cadeau)
CREATE TABLE enfant_gentil (
   enfant_id INT,
   cadeau_id INT,
   PRIMARY KEY(enfant_id, cadeau_id),
   FOREIGN KEY (enfant_id, cadeau_id) REFERENCES cadeau(enfant_id, cadeau_id)
);

--Table livraison
CREATE TABLE livraison (
    ->    id INT,
    ->    date_livraison DATE,
    ->    date_arrive DATE,
    ->    PRIMARY KEY(id)
    -> );

--Table planification

--Table atelier

--Table lutin
MariaDB [Noel]> CREATE TABLE lutin (
    ->    id INT,
    ->    nom VARCHAR(50),
    ->    PRIMARY KEY(id)
    -> );

--Table livrcadeau
 CREATE TABLE cadeau_livraison (
    ->     enfant_id INT,
    ->     cadeau_id INT,
    ->     livraison_id INT,
    ->     PRIMARY KEY (enfant_id, cadeau_id, livraison_id),
    ->     FOREIGN KEY (enfant_id, cadeau_id) REFERENCES cadeau(enfant_id, cadeau_id),
    ->     FOREIGN KEY (livraison_id) REFERENCES livraison(id)
    -> );

--
 CREATE TABLE Asso_8 (
    ->    id INT,        -- Référence à atelier.id
    ->    id_1 INT,      -- Référence à lutin.id
    ->    PRIMARY KEY(id, id_1),
    ->    FOREIGN KEY(id) REFERENCES atelier(id),
    ->    FOREIGN KEY(id_1) REFERENCES lutin(id)
    -> );
--Table coordonnee
INSERT INTO coordonnee (id, pays, ville) VALUES
(1, 'France', 'Paris'),
(2, 'Canada', 'Montréal'),
(3, 'Japon', 'Tokyo');
UPDATE coordonnee SET rue = '12 rue de la Paix' WHERE id = 1;
UPDATE coordonnee SET rue = '88 avenue des Neiges' WHERE id = 2;
UPDATE coordonnee SET rue = '5-1-1 Harajuku' WHERE id = 3;


--Table nom_cadeau
INSERT INTO nom_cadeau (id, nom, type) VALUES
(1, 'Train miniature', 'Jouet'),
(2, 'Livre de contes', 'Livre'),
(3, 'Poupée', 'Jouet'),
(4, 'Boîte de Lego', 'Jeu de construction')
(5, 'Camion de pompier', 'Jouet'),
(6, 'Puzzle animaux', 'Jeu éducatif'),
(7, 'Fifa 17', 'jeu vidéo'),
(8, 'Coloriage magique', 'Activité'),
(9, 'Microscope junior', 'Jeu scientifique'),
(10, 'Kit de peinture', 'Activité créative'),
(11, 'Jeu de société - Uno', 'Jeu de société'),
(12, 'Balle rebondissante', 'Sport'),
(13, 'Harmonica', 'Instrument de musique'),
(14, 'just dance', 'jeu vidéo'),
(15, 'Voiture télécommandée', 'Jouet électronique');

--Table enfant
INSERT INTO enfant (id, nom, prenom, adresse) VALUES
(1, 'Dupont', 'Léo', 1),
(2, 'Martin', 'Chloé', 2),
(3, 'Tanaka', 'Haruto', 3);

--Table cadeau
INSERT INTO cadeau (enfant_id, cadeau_id) VALUES
(1, 1),  -- Léo reçoit un train miniature
(1, 2),  -- Léo reçoit aussi un livre de contes
(2, 3),  -- Chloé reçoit une poupée
(3, 4);  -- Haruto reçoit une boîte de Lego

--Table enfant_gentil
INSERT INTO enfant_gentil (enfant_id, cadeau_id) VALUES
(1, 1),
(1, 2),
(3, 4);

-- Livraison
INSERT INTO livraison (id, date_livraison, date_arrive)
VALUES 
  (1, '2025-12-20', '2025-12-24'),
  (2, '2025-12-21', '2025-12-25');


--Lutin
INSERT INTO lutin (id, nom)
VALUES 
  (1, 'Léo'),
  (2, 'Noisette');

-- atelier
NSERT INTO atelier (id, nom, capacite_max)
VALUES 
  (1, 'Atelier Nord', 10),
  (2, 'Atelier Sud', 8)
  (3, 'Atelier Est', 12);

  --planif
  INSERT INTO planification (id, id_cadeau, id_atelier, date_fabrication)
VALUES 
  (1, 1, 1, '2025-11-15'),
  (2, 2, 2, '2025-11-20');

--cad-liv
INSERT INTO cadeau_livraison (enfant_id, cadeau_id, livraison_id)
VALUES 
  (1, 1, 1),
  (1, 2, 2);

--at-lut
INSERT INTO Asso_8 (id, id_1)
VALUES 
  (1, 1),
  (2, 2);

