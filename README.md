# BidWell

## Le projet

BidWell est une application Web permettant la vente aux enchères d'objets de collection en tout genre.

## Le système d'enchères

Le système utilisé est un mélange hybride entre une enchère ascendante et une enchère descendante.

## Accès au site

### URL

[192.168.14.212](http://192.168.14.212) (accessible seulement depuis le réseau local de l'IUT2)

__Attention__ : Votre navigateur va probablement vous afficher un message de prévention la première fois que vous accédez
aux pages 'Connexion' ou 'Inscription'. Cela est dû au fait que nous utilisons le protocole HTTPS sur ces pages afin que
les mots de passe soient échangés de manière encryptés sur le réseau mais comme nous n'avions ni le temps, ni le budget de
faire valider notre certification HTTPS par un organisme de certification reconnu, votre navigateur le prend comme une menace.

### Utilisateur lambda du site

login : test

mot de passe : testtest

### Compte PayPal sandbox

email : sb-toyml24883351@personal.example.com 

mot de passe : Yi3g:#<R

## Lancer le site en local

Avant de lancer le site en local sur vos machines à partir de ce repo, il faudra, si cela n'est pas déjà fait :

 - executer `composer update` dans le dossier Bidwell afin de récupérer les dépendances de notre application
 - lancer `sqlite3 database.db` dans Bidwell/data puis `.read create.sql` pour créer la base de données
(et éventuellement `.read insert.sql` pour la peupler)

Puis pour lancer le site :

 - executer `php bin/server.php` depuis le dossier Bidwell afin de démarer le serveur de websockets
 - ouvrir un serveur web php dans le dossier Bidwell (avec la commande `php -S localhost:8000` par exemple)
