CREATE TABLE enchere (
	id INTEGER PRIMARY KEY AUTO_INCREMENT,
	libelle TEXT,
	dateDebut INTEGER,
	prixDepart REAL,
	prixRetrait REAL,
	prixDerniereEnchere REAL,
	images TEXT,
	description TEXT	
);

CREATE TABLE utilisateur (
	login TEXT PRIMARY KEY,
	email TEXT,
	mdpHash TEXT,
	nom TEXT,
	numeroDeTelephone TEXT UNIQUE,
	nbJetons INTEGER
);

CREATE TABLE categorie (
	id INTEGER PRIMARY KEY AUTO_INCREMENT,
	libelle TEXT,
	idMere INTEGER REFERENCES categorie(id)
);

CREATE TABLE participation (
	idEnchere INTEGER AUTO_INCREMENT,
	loginUtilisateur TEXT,
	nbEncheres INTEGER,
	PRIMARY KEY (idEnchere, loginUtilisateur),
	FOREIGN KEY (idEnchere) REFERENCES enchere(id),
	FOREIGN KEY (loginUtilisateur) REFERENCES utilisateur(login)
);

