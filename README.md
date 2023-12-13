# BidWell

## Le projet

BidWell est une application Web permettant la vente aux enchères d'objets de collection en tout genre.

## Le système d'enchères

Le système utilisé est un mélange hybride entre une enchère ascendante et une enchère descendante.

## Accès au site

### Lancer le site en local

Avant de lancer le site en local sur vos machines à partir de ce repo, il faudra, si cela n'est pas déjà fait :

- executer `composer install` dans le dossier Bidwell afin de récupérer les dépendances de notre application
- lancer `sqlite3 database.db` dans Bidwell/data puis `.read create.sql` pour créer la base de données
  (et éventuellement `.read insert.sql` pour la peupler)

Puis pour lancer le site :

- executer `php bin/server.php` depuis le dossier Bidwell afin de démarer le serveur de websockets
- ouvrir un serveur web php dans le dossier Bidwell (avec la commande `php -S localhost:8000` par exemple)
