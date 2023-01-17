-- Lecture des fichiers pour réinitialiser la base de données
.read drop.sql
.read create.sql


-- Insertions dans la base de données

-- Utilisateur

INSERT INTO Utilisateur
VALUES("alexv", "hash1", "Alexandre Vasseur", "alexv@gmail.com", "0642791382", 2, 1831707963),
VALUES("suzannedlb", "hash2", "Suzanne de la Besnard", "suzannedlb@gmail.com", "0604701322", 5, 1831707963),
VALUES("rrmargaux", "hash3", "Margaux Renault-Rey", "rr.margaux@gmail.com", "0604386570", 3, 1831707963),
VALUES("victex", "hash4", "Victor Texier", "victex@gmail.com", "0604385129", 3, 1831707963),
VALUES("manuparis", "hash5", "Emmanuelle Paris", "manuparis@gmail.com", "0601984101", 4, 1831707963),
VALUES("flmich", "hash6", "Michel Foucher-Lebon", "michel.fl@gmail.com", "0604780461", 5, 1831707963),
VALUES("aaaaaaaa", "hash7", "Adrienne-Andrée Royer", "aaaaaaa@outlook.fr", "0640751914", 1, 1831707963),
VALUES("t.couz", "hash8", "Tristan Cousin", "t.couz@outlook.fr", "0640480615", 6, 1831707963),
VALUES("m.momo", "hash9", "Monique Marie", "marie.monique@laposte.net", "0604581695", 5, 1831707963),
VALUES("ljack", "hash10", "Jacqueline Leger", "ljack@laposte.net", "0644182608", 5, 1831707963);

-- Categorie
-- Pierres précieuses
INSERT INTO Categorie VALUES("Pierres précieuses",NULL);
INSERT INTO Categorie VALUES("Amethystes","Pierres précieuses");
INSERT INTO Categorie VALUES("Diamants","Pierres précieuses");
INSERT INTO Categorie VALUES("Emeraudes","Pierres précieuses");
INSERT INTO Categorie VALUES("Saphirs","Pierres précieuses");
INSERT INTO Categorie VALUES("Autres pierres","Pierres précieuses");
-- Objets de collection
INSERT INTO Categorie VALUES("Objets de collection",NULL);
INSERT INTO Categorie VALUES("Timbres","Objets de collection");
INSERT INTO Categorie VALUES("Monnaies","Objets de collection");
INSERT INTO Categorie VALUES("Cartes pokémon","Objets de collection");
INSERT INTO Categorie VALUES("Autres cartes","Objets de collection");
-- Jouets
INSERT INTO Categorie VALUES("Jouets",NULL);
INSERT INTO Categorie VALUES("LEGO","Jouets");
INSERT INTO Categorie VALUES("Playmobil","Jouets");
INSERT INTO Categorie VALUES("Autres jouets","Jouets");
-- Objet d'arts
INSERT INTO Categorie VALUES("Objets d'art",NULL);
INSERT INTO Categorie VALUES("Peinture","Objets d'art");
INSERT INTO Categorie VALUES("Sculpture","Objets d'art");
INSERT INTO Categorie VALUES("Dessins","Objets d'art");
INSERT INTO Categorie VALUES("Autres objets d'art","Objets d'art");
-- Autres
INSERT INTO Categorie VALUES("Autres",NULL);
INSERT INTO Categorie VALUES("Voitures","Autres");
INSERT INTO Categorie VALUES("Livres et Manuscrits","Autres");
INSERT INTO Categorie VALUES("Autre","Autres");

-- Enchere
INSERT INTO Enchere(
	loginCreateur, 
	libelle,
	dateDebut,
	prixDepart,
	prixRetrait,
	images, 
	description,
	libelleCategorie,
	infosenvoi, 
	infoscontact, 
	dateFinConservation
)
VALUES("victex", "Carte Pokémon Mewtwo XY Évolutions", 1676622398, 60, 10, "1_1.webp", "Carte Pokémon rare de l'extension XY Évolutions.", "Cartes pokémon", "true", "true", 1739780798),
VALUES("rrmargaux", "Timbre Louis Pasteur", 1676622398, 60, 10, "2_1.webp", "Timbre d'Andorre à l'effigie de Louise Pasteur", "Timbres", "true", "true", 1739780798),
/* VALUES("alexv", "", 1676622398, 60, 10, "2_1.webp", "Timbre d'Andorre à l'effigie de Louise Pasteur", "Timbres", "true", "true", 1739780798), */

