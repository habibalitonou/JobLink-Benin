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

