-- Table coodonné -> enfant.adresse
CREATE TABLE coordonée(
   id INT,
   pays VARCHAR(50),
   ville VARCHAR(50),
   PRIMARY KEY(id)
);

--ref le cadeau
CREATE TABLE nom_cadeau(
   id INT,
   nom VARCHAR(50),
   type VARCHAR(50),
   PRIMARY KEY(id)
);

-- ref les enfants
CREATE TABLE Enfant(
   ID INT,
   Nom VARCHAR(50),
   Prenom VARCHAR(50),
   adresse VARCHAR(50),
   id_1 INT NOT NULL,
   PRIMARY KEY(ID),
   FOREIGN KEY(id_1) REFERENCES coordonée(id)
);

--ref pour les cadeaux
CREATE TABLE cadeau(
   id VARCHAR(50),
   id_1 INT NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY(id_1) REFERENCES nom_cadeau(id)
);

-- ref les enfants gentil et méchant
CREATE TABLE enfant_gentil(
   ID INT,
   id_1 VARCHAR(50),
   sage LOGICAL,
   PRIMARY KEY(ID, id_1),
   FOREIGN KEY(ID) REFERENCES Enfant(ID),
   FOREIGN KEY(id_1) REFERENCES cadeau(id)
);
