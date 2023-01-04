CREATE TABLE Utilisateur (
	login TEXT PRIMARY KEY,
	email TEXT,
	mdpHash TEXT,
	nom TEXT,
	numeroDeTelephone TEXT UNIQUE,
	nbJetons INTEGER
);

CREATE TABLE Categorie (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	libelle TEXT,
	idMere INTEGER REFERENCES Categorie(id)
);

CREATE TABLE Enchere (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	idCategorie INTEGER,
	libelle TEXT,
	dateDebut INTEGER,
	prixDepart REAL,
	prixRetrait REAL,
	loginUtilisateurDerniereEnchere TEXT,
	images TEXT,
	description TEXT,
	FOREIGN KEY (idCategorie) REFERENCES Categorie(id),
	FOREIGN KEY (loginUtilisateurDerniereEnchere) REFERENCES Utilisateur(login)
);

CREATE TABLE Participation (
	idEnchere INTEGER,
	loginUtilisateur TEXT,
	nbEncheres INTEGER,
	montantDerniereEnchere REAL,
	PRIMARY KEY (idEnchere, loginUtilisateur),
	FOREIGN KEY (idEnchere) REFERENCES Enchere(id),
	FOREIGN KEY (loginUtilisateur) REFERENCES Utilisateur(login)
);
