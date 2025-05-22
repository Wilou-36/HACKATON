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

