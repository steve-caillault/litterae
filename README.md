# Litterae

Il s'agit d'un site permettant de gérer les livres d'une bibliothèque personnelle. Il permet de :
* gérer la liste des livres lus, possédés et souhaités
* gérer la liste des auteurs à suivre

## Installation
 
PHP 7.4 est requis pour faire fonctionner ce projet. Ce projet est compatible avec MySQL 8 ou MariaDB 10.1.

Après avoir récupéré le projet, exécuter la commande suivante à la racine :

````bash
php cli environment demo
````

Cela permet de déclarer l'environnement à exécuter.

## Initialisation des bases de données

Vous pouvez exécuter les requêtes du projet présent dans le fichier /database/initialize-demo.sql.
Deux bases de données seront créées :
* books_demo_resources, pour stocker les images
* books_demo, pour stocker les données des livres et des contributeurs

## Configuration

Dans le fichier /config/environments/demo/database.php, adaptez la connexion aux deux bases de données pour votre installation.

## Création d'un compte utilisateur

En ligne de commande, vous pouvez exécuter la commande du type :

````bash
php cli create-user firstName lastName password role
````

*firstName* et *lastName* seront utilisés pour déterminer l'identifiant de l'utilisateur. Par exemple, si le prénom de l'utilisateur est Fiodor et le nom Dostoïevski, le nom d'utilsateur sera fiodor-dostoievski. Pour le paramètre role, *admin* et *reader* sont autorisés. Un utilisateur qui a le rôle *admin* a automatiquement le rôle *reader*. 

## Panneau d'administration

Le panneau d'administration est disponible à l'adresse /admin. L'accès est restreint aux utilisateurs ayant le rôle *admin*.

### Ajouter un livre

Pour ajouter un livre dans la bibliothèque, il vous faudra auparavant créer l'éditeur ou la collection.
Les contributeurs (auteurs, traducteurs et illustrateurs) peuvent être ajouter après la création du livre.
Les contributeurs sont des personnes que vous pourrez, après les avoir créé, associer à un rôle sur la page d'édition d'un livre.
Une personne est liée à un pays. Il faudra créer un pays avant de pouvoir ajouter une personne.
Aucune image n'est obligatoire (pays ou livre), mais un livre est plus identifiable par sa couverture.
