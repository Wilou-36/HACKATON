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


--Table coordonnee
INSERT INTO coordonnee (id, pays, ville) VALUES
(1, 'France', 'Paris'),
(2, 'Canada', 'Montréal'),
(3, 'Japon', 'Tokyo');

--Table nom_cadeau
INSERT INTO nom_cadeau (id, nom, type) VALUES
(1, 'Train miniature', 'Jouet'),
(2, 'Livre de contes', 'Livre'),
(3, 'Poupée', 'Jouet'),
(4, 'Boîte de Lego', 'Jeu de construction');

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

