-- delete le contenu de toutes les tables
DELETE FROM Participation;
DELETE FROM Enchere;
DELETE FROM Categorie;
DELETE FROM Utilisateur;

-- remet les sequences d'incrementation auto des id à 0
UPDATE sqlite_sequence SET seq = 0 WHERE name = 'Enchere';