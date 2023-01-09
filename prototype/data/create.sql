CREATE TABLE Utilisateur (
	login TEXT PRIMARY KEY,
	mdpHash TEXT NOT NULL,
	nom TEXT NOT NULL,
	email TEXT NOT NULL,
	numeroTelephone TEXT UNIQUE NOT NULL,
	nbJetons INTEGER NOT NULL,
	dateFinConservation INTEGER NOT NULL
);

CREATE TABLE Categorie (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	libelle TEXT NOT NULL,
	idMere INTEGER,		-- NULL si c'est la Categorie racine
	FOREIGN KEY (idMere) REFERENCES Categorie(id)
);

CREATE TABLE Enchere (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	libelle TEXT NOT NULL,
	dateDebut INTEGER NOT NULL,
	prixDepart REAL NOT NULL,
	prixRetrait REAL NOT NULL,
	loginUtilisateurDerniereEnchere TEXT,
	images TEXT NOT NULL,
	description TEXT NOT NULL,
	idCategorie INTEGER NOT NULL,
	dateFinConservation INTEGER NOT NULL,
	FOREIGN KEY (idCategorie) REFERENCES Categorie(id),
	FOREIGN KEY (loginUtilisateurDerniereEnchere) REFERENCES Utilisateur(login)
);

CREATE TABLE Participation (
	idEnchere INTEGER,
	loginUtilisateur TEXT,
	nbEncheres INTEGER NOT NULL,
	montantDerniereEnchere REAL,
	dateFinConservation INTEGER NOT NULL,
	PRIMARY KEY (idEnchere, loginUtilisateur),
	FOREIGN KEY (idEnchere) REFERENCES Enchere(id),
	FOREIGN KEY (loginUtilisateur) REFERENCES Utilisateur(login)
);
