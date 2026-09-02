# HeritageV1

## Partie 1 — Initialisation et Versionnement

### 1. Pourquoi `/vendor` ne doit-il pas être versionné ?
Ce dossier contient les dépendances externes installées automatiquement via Composer. Elles ne sont pas écrites par nous, occupent beaucoup d'espace disque et peuvent être réinstallées à tout moment à l'identique avec la commande `composer install` à partir du fichier `composer.json` (et `composer.lock`). Les versionner alourdirait inutilement le dépôt Git et créerait des risques de conflits ou d'incohérences entre différents environnements.

### 2. Différence entre un commit et un tag ?
- **Commit** : C'est un instantané (*snapshot*) de l'état du projet à un moment donné, accompagné d'un identifiant unique (hash SHA-1) et d'un message descriptif. L'historique Git est une succession de commits qui évolue au fil du développement.
- **Tag** : C'est une étiquette fixe pointant vers un commit précis, principalement utilisée pour marquer une version spécifique ou une release stable du projet (ex : `v0.0.0`, `v0.3.0`). Contrairement à une branche, un tag est immuable et ne progresse pas avec les nouveaux commits.

### 3. Pourquoi la branche `main` doit-elle rester stable ?
La branche `main` représente la version de référence en production du projet. Si `main` contient du code instable ou cassé, cela peut bloquer l'équipe, corrompre les déploiements et complexifier la détection des bugs. C'est pourquoi le développement s'effectue sur des branches secondaires dédiées (ex: `dev`, `partie/03-database`) et seules les fonctionnalités testées et validées sont fusionnées sur `main`.

---

## Partie 2 — Architecture et Front Controller

### 1. Pourquoi placer `index.php` dans un dossier `public` ?
Le dossier `public/` est le seul dossier exposé directement au serveur web et accessible par le navigateur. Y placer `index.php` permet de sanctuariser tout le reste du code source (entités, contrôleurs, services, configuration, fichiers `.env`, base de données) hors de portée des accès directs HTTP.

### 2. Pourquoi toutes les requêtes devraient-elles passer par ce fichier ?
`public/index.php` agit comme un **Front Controller** (Point d'entrée unique). Toutes les requêtes HTTP passent par lui, ce qui permet de centraliser :
- Le chargement automatique des classes via Composer (`autoload.php`).
- La configuration et l'initialisation de l'application.
- Le routage vers les bons contrôleurs.
- La gestion globale des erreurs et des exceptions.

```text
Navigateur
    ↓
public/index.php (Front Controller)
    ↓
Routeur
    ↓
Controller
    ↓
Service
    ↓
Repository
    ↓
Base de données (PostgreSQL)
```

### 3. Quels éléments ne devraient jamais se trouver dans le dossier `public` ?
Le dossier `public` ne doit contenir aucun élément sensible ou logique interne, notamment :
- Les classes métier (`Entity`, `DTO`).
- Les couches applicatives (`Repository`, `Service`, `Controller`, `Database`).
- Les fichiers de configuration (`.env`, `composer.json`, etc.).
- Les scripts SQL et sauvegardes de bases de données.

Seuls les fichiers statiques publics (CSS, JS, images) et le fichier `index.php` ont leur place dans `public/`.

### 4. Comment avez-vous réparti les responsabilités entre vos dossiers ?
- `src/Entity/` : Représente les entités du domaine métier (`AbstractDocument`, `CopieExamen`) avec leurs règles de validation.
- `src/Database/` : Gère l'accès et la connexion unique à la base de données via PDO (Singleton).
- `src/Repository/` : Contient les requêtes SQL et la persistance des données.
- `src/service/` : Contient la logique applicative et les traitements métier.
- `src/Controller/` : Réceptionne les requêtes et prépare les réponses.
- `public/` : Point d'entrée HTTP public (`index.php`).
- `database/` : Scripts de création du schéma (`schema.sql`).
- `templates/` : Vues HTML / PHP pour le rendu.

---

## Partie 3 — Préparer la persistance

### Procédure de mise en place de la base de données

#### 1. Création de la base de données
Exécutez la commande PostgreSQL suivante pour créer la base de données :
```bash
createdb -U postgres -h 127.0.0.1 -p 5432 heritage_v1
```
*(ou via l'invite `psql` : `CREATE DATABASE heritage_v1;`)*

#### 2. Exécution du script de schéma
Le script `database/schema.sql` contient la structure de la table `copie_examen` ainsi qu'une ligne de test.
Pour l'exécuter :
```bash
psql -U postgres -h 127.0.0.1 -p 5432 -d heritage_v1 -f database/schema.sql
```

#### 3. Ajouter une ligne de test
La table `copie_examen` accepte les données d'évaluation :
```sql
INSERT INTO copie_examen (date_depot, date_limite, note_brute, note_finale, penalite_appliquee)
VALUES ('2026-09-01 10:00:00', '2026-09-05 23:59:59', 15.50, 15.50, FALSE);
```

#### 4. Consulter les données
```sql
SELECT * FROM copie_examen;
```

#### 5. Configuration de la connexion PDO et variables d'environnement
1. Copier le fichier d'exemple :
   ```bash
   cp .env.example .env
   ```
2. Renseigner vos accès dans le fichier `.env` :
   ```env
   DB_DRIVER=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_NAME=heritage_v1
   DB_USER=postgres
   DB_PASSWORD=votre_mot_de_passe
   ```
3. La classe `App\Database\Database` charge automatiquement ces paramètres et fournit l'instance PDO via `Database::getConnection()`.

---

### Questions théoriques et réponses

#### 1. Quelle classe doit être responsable de la connexion ?
La responsabilité de la connexion doit être confiée à une classe technique dédiée (ici `App\Database\Database` ou une classe `ConnectionFactory`), située dans une couche d'infrastructure ou de configuration. 

Les entités métiers (`Entity`) ne doivent en aucun cas gérer la connexion, respectant ainsi le **principe de responsabilité unique (SRP)**. Les classes `Repository` utiliseront ensuite cette connexion pour exécuter leurs requêtes SQL sans avoir à se soucier de son instanciation.

#### 2. Faut-il créer une nouvelle connexion pour chaque requête SQL ?
**Non, absolument pas.** 

Ouvrir une nouvelle connexion à chaque requête SQL entraîne un surcoût considérable en temps et en ressources (négociation TCP, authentification auprès du SGBD, allocation mémoire). Il est recommandé d'utiliser une **connexion unique partagée** tout au long du traitement de la requête HTTP. Cela s'implémente généralement via le patron de conception **Singleton** ou par **injection de dépendance**.

#### 3. Où placer les identifiants de connexion ?
Les identifiants (hôte, utilisateur, mot de passe, nom de la base) doivent être stockés **hors du code source**, dans un fichier de configuration d'environnement (ex : fichier `.env`).

Ce fichier `.env` doit être strictement listé dans `.gitignore` afin de ne jamais être envoyé sur le dépôt Git. Un fichier `.env.example` (contenant les clés sans les mots de passe) est versionné pour indiquer les variables requises.

#### 4. Pourquoi utiliser PDO ?
- **Abstraction et portabilité** : PDO (*PHP Data Objects*) fournit une interface orientée objet unique pour interagir avec différents SGBD (PostgreSQL, MySQL, SQLite, Oracle...). Le code applicatif reste homogène si le SGBD change.
- **Sécurité renforcée (Requêtes préparées)** : PDO permet l'utilisation native de requêtes préparées (`prepare()` et `execute()`), éliminant ainsi les risques d'**injection SQL** grâce à la séparation stricte de la structure SQL et des données utilisateurs.
- **Gestion moderne des erreurs** : Avec le mode `PDO::ERRMODE_EXCEPTION`, toute erreur de requête déclenche une exception `PDOException`, facile à intercepter et à traiter proprement.
- **Formatage des résultats flexible** : PDO facilite la récupération des données sous différents formats (tableaux associatifs avec `FETCH_ASSOC`, objets, ou directement hydratés dans une classe spécifique avec `FETCH_CLASS`).
