CREATE TABLE Utilisateur (
	login TEXT PRIMARY KEY,
	mdpHash TEXT NOT NULL,
	nom TEXT NOT NULL,
	email TEXT NOT NULL,
	numeroTelephone TEXT UNIQUE NOT NULL,
	nbJetons INTEGER NOT NULL,
	text-decoration: none;
        color: var(--couleur-noir);
        background-color: var(--couleur-jaune);
        border-radius: 1rem;
        white-space: nowrap;
        display: block;
        padding: 0.5rem 0.5rem; INTEGER NOT NULL
);

CREATE TABLE Categorie (
	libelle TEXT PRIMARY KEY,
	libelleMere INTEGER,		-- NULL si c'est la Categorie racine
	FOREIGN KEY (libelleMere) REFERENCES Categorie(libelle)
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
	libelleCategorie INTEGER NOT NULL,
	dateFinConservation INTEGER NOT NULL,
	FOREIGN KEY (libelleCategorie) REFERENCES Categorie(libelle),
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
