.separator |
.import Categorie.txt Categorie
UPDATE Categorie SET libelleMere = NULL WHERE libelleMere = 'NULL';
.import Utilisateur.txt Utilisateur
.import Enchere.txt Enchere
UPDATE Enchere SET loginUtilisateurDerniereEnchere = NULL WHERE loginUtilisateurDerniereEnchere = 'NULL';
