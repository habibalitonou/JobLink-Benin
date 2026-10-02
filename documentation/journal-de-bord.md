# Journal de bord — JobLink Bénin

## 30/09/2026

### Travail réalisé
- Lecture et analyse du cahier des charges.
- Identification des trois espaces du projet :
  - portail public WordPress ;
  - espace candidat en PHP orienté objet ;
  - espace administrateur en PHP orienté objet.
- Création du dossier de documentation du projet.
- Préparation des fichiers de suivi :
  - decisions-techniques.md
  - journal-de-bord.md
  - preparation-soutenance.md

### Objectif de la prochaine étape
- Définir l'architecture du projet.
- Concevoir le MCD.
- Définir les relations entre les différentes entités.
## 01/10/2026 — Classe Administrateur et accès aux données

- Création de la classe `classes/Administrateur.php`.
- Ajout des propriétés privées et du constructeur.
- Ajout des getters et setters pour les informations de l'administrateur.
- Ajout de la méthode `findByEmail()` utilisant la connexion PDO fournie par la classe `Database`.
- Utilisation d'une requête préparée pour rechercher un administrateur par son adresse e-mail.
- Test fonctionnel réalisé avec le compte administrateur de test présent dans la base de données.
- Résultat du test : administrateur correctement retrouvé avec son identifiant, son nom, son prénom et son adresse e-mail.
- Suppression du fichier temporaire utilisé pour le test.


## 01/10/2026 — Classe Entreprise et accès aux données

- Création de la classe `classes/Entreprise.php`.
- Ajout des propriétés privées et du constructeur.
- Ajout des getters et setters pour l'identifiant, le nom, l'e-mail, le téléphone, le secteur et la ville.
- Ajout de la méthode `findById()` utilisant la connexion PDO fournie par la classe `Database`.
- Utilisation d'une requête préparée pour rechercher une entreprise par son identifiant.
- Test fonctionnel réalisé avec l'entreprise de test `TechnoBénin`.
- Résultat du test : entreprise correctement retrouvée avec ses informations et ses identifiants de secteur et de ville.
- Suppression du fichier temporaire utilisé pour le test.


## 01/10/2026 — Classe Offre et accès aux données

- Création de la classe `Offre` dans `classes/Offre.php`.
- Définition des propriétés privées correspondant aux informations d'une offre d'emploi.
- Mise en place du constructeur pour initialiser les données de l'offre.
- Ajout des getters et setters pour les propriétés de la classe.
- Ajout de la méthode `findById()` utilisant PDO et une requête préparée.
- Test de récupération de l'offre ID 1 depuis la base de données.
- Résultat du test : offre « Développeur Web PHP » récupérée correctement, avec un salaire de 450000 et l'ID entreprise 1.
- Suppression du fichier temporaire utilisé pour le test.

## 01/10/2026 — Classe Candidature et accès aux données

- Création de la classe `Candidature` dans `classes/Candidature.php`.
- Définition des propriétés privées correspondant aux informations d'une candidature.
- Mise en place du constructeur pour initialiser les données de la candidature.
- Ajout des getters et setters pour les propriétés de la classe.
- Ajout de la méthode `findById()` utilisant PDO et une requête préparée.
- Test de récupération d'une candidature temporaire depuis la base de données.
- Résultat du test : candidature correctement retrouvée avec son identifiant, l'identifiant du candidat, l'identifiant de l'offre et son statut.
- Suppression du fichier temporaire utilisé pour le test.
- Suppression de la candidature temporaire après validation du test.
\n

## 01/10/2026 — Classe Secteur et accès aux données

- Création de la classe `Secteur` dans `classes/Secteur.php`.
- Définition des propriétés privées correspondant aux informations d'un secteur.
- Mise en place du constructeur pour initialiser le libellé du secteur.
- Ajout des getters et setters pour les propriétés de la classe.
- Ajout de la méthode `findById()` utilisant PDO et une requête préparée.
- Test de récupération du secteur ID 1 depuis la base de données.
- Résultat du test : secteur « Informatique » récupéré correctement.
- Suppression du fichier temporaire utilisé pour le test.

## 01/10/2026 — Classe Ville et accès aux données

- Création de la classe `Ville` dans `classes/Ville.php`.
- Définition des propriétés privées correspondant aux informations d'une ville.
- Mise en place du constructeur pour initialiser le libellé de la ville.
- Ajout des getters et setters pour les propriétés de la classe.
- Ajout de la méthode `findById()` utilisant PDO et une requête préparée.
- Test de récupération de la ville ID 1 depuis la base de données.
- Résultat du test : ville « Cotonou » récupérée correctement.
- Suppression du fichier temporaire utilisé pour le test.

## 01/10/2026 — Classe TypeContrat et accès aux données

- Création de la classe `TypeContrat` dans `classes/TypeContrat.php`.
- Définition des propriétés privées correspondant aux informations d'un type de contrat.
- Mise en place du constructeur pour initialiser le libellé du type de contrat.
- Ajout des getters et setters pour les propriétés de la classe.
- Ajout de la méthode `findById()` utilisant PDO et une requête préparée.
- Test de récupération du type de contrat ID 1 depuis la base de données.
- Résultat du test : type de contrat « CDI » récupéré correctement.
- Suppression du fichier temporaire utilisé pour le test.


## 01/10/2026 — Classe Candidat et accès aux données

- Création de la classe `Candidat` dans `classes/Candidat.php`.
- Ajout des attributs privés correspondant à la table `candidat`.
- Ajout du constructeur ainsi que des getters et setters.
- Ajout de la méthode `findById()` pour récupérer un candidat depuis la base de données.
- Utilisation de PDO et d'une requête préparée avec un paramètre nommé.
- Prise en compte des champs nullable `id_ville` et `cv_fichier`.
- Prise en compte de `date_inscription`, générée automatiquement par MySQL.
- Vérification syntaxique avec `php -l classes/Candidat.php`.
- Test fonctionnel avec le candidat de test ID 1.
- Résultat : le candidat Ahouandjinou Marc a été correctement récupéré depuis la base de données.
- Suppression du fichier temporaire `test-candidat.php` après le test.


## 01/10/2026 — Déconnexion et destruction de la session administrateur

- Création du fichier `admin-joblink/logout.php`.
- Suppression des données présentes dans la session administrateur.
- Suppression du cookie de session lorsqu'il est utilisé.
- Destruction de la session avec `session_destroy()`.
- Redirection vers la page de connexion après la déconnexion.
- Vérification syntaxique avec `php -l admin-joblink/logout.php`.
- Test fonctionnel dans le navigateur : après déconnexion, l'accès direct à `index.php` redirige vers `login.php`.
- Résultat : la déconnexion et la protection de la session administrateur fonctionnent correctement.


## 01/10/2026 — Ajout de la méthode findAll() dans la classe Entreprise

- Ajout de la méthode statique `findAll()` dans `classes/Entreprise.php`.
- La méthode utilise la connexion PDO fournie par la classe `Database`.
- Utilisation d'une requête préparée pour récupérer les entreprises.
- Les entreprises sont triées par nom avec `ORDER BY nom ASC`.
- Chaque résultat de la base est transformé en objet `Entreprise`.
- Test fonctionnel réalisé avec les données de test de la base JobLink.
- Résultat : 5 entreprises récupérées correctement.
- Vérification des identifiants, noms et adresses e-mail des entreprises.
- Le fichier temporaire utilisé pour le test a ensuite été supprimé.

## 01/10/2026 — Création de la page de liste des entreprises

- Création de la page `admin-joblink/entreprises/index.php`.
- Protection de la page par vérification de la session administrateur.
- Chargement des classes `Database` et `Entreprise`.
- Utilisation de `Entreprise::findAll()` pour récupérer les entreprises.
- Affichage des entreprises dans un tableau avec leurs principales informations.
- Utilisation de `htmlspecialchars()` pour sécuriser l'affichage des données textuelles.
- Test fonctionnel réalisé dans le navigateur avec les 5 entreprises de test.
- Résultat : la liste des entreprises s'affiche correctement dans l'espace d'administration.

## 02/10/2026 — Ajout de la méthode create() dans la classe Entreprise

- Ajout de la méthode `create()` dans `classes/Entreprise.php`.
- Utilisation d'une requête préparée PDO pour insérer une entreprise.
- Récupération de l'identifiant auto-incrémenté avec `lastInsertId()`.
- Affectation de l'identifiant généré à l'objet `Entreprise`.
- Vérification syntaxique de la classe après modification.
- Test fonctionnel réalisé avec une entreprise temporaire.
- Résultat : insertion réussie et identifiant généré correctement récupéré.
- L'entreprise temporaire a ensuite été supprimée de la base de données.
- Le fichier temporaire utilisé pour le test a également été supprimé.

## 02/10/2026 — Ajout de la méthode findAll() dans la classe Ville

- Ajout de la méthode statique `findAll()` dans `classes/Ville.php`.
- Utilisation de PDO et d'une requête préparée pour récupérer les villes.
- Tri des villes par libellé dans l'ordre alphabétique.
- Transformation des résultats SQL en objets `Ville`.
- Vérification de la syntaxe PHP effectuée avec `php -l classes/Ville.php`.
- Test fonctionnel effectué avec un fichier temporaire.
- Résultat : 8 villes récupérées correctement depuis la base de données.
- Le fichier temporaire de test a ensuite été supprimé.
