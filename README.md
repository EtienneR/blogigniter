blogigniter
===========

Blogigniter est un système de blog codé à partir du framework Codeigniter (PHP) avec une BDD MySQL.
/!\ C'est une version béta /!\


## Fonctionnalités

### Coté admin
* Ecrire un article au format markdown.
* Rattacher un article à une et une seule rubrique .
* Publier un article à une date de publication.
* Taguer les articles.
* Système d'upload d'image pour l'image d'illustration d'un article.
* Modération des commentaires.
* 2 niveaux d'utilisateurs : "admin" ou "modérateur".
* Paramétrer les options du site (level admin).
* Afficher les mots recherchés en front (level admin).

### Coté front
* Flux RSS.
* Moteur de recherche.
* Système de commentaires (captcha intégré).


## Plugins externes utilisés

### CSS
Twitter Bootstrap
### Javascript
Bibiliothèque jQuery
Twitter Bootstrap

### PHP
Librairie Parsedown


## Installation

1. Dézippez (ou clonez) le dossier de Blogigniter dans votre dossier "www" (par défaut) d'Apache.
2. Importez le fichier "blogigniter.sql" dans la BDD MySQL "blogigniter" (par défaut).
3. Réglez vos paramètres de connexion à votre serveur MySQL : application/config/database.php (lignes 51, 52 & 53).
Pour utiliser le back office (http://127.0.0.1/blogigniter/admin), le login et le mot de passe sont "admin" / "admin" (à changer par vos soins ^^).