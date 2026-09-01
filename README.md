# HeritageV1

# 1 . Pourquoi /vendor ne doit-il pas être versionné ?

Ce dossier contient généralement des dépendances externes (bibliothèques tierces installées automatiquement, par exemple via Composer, npm, etc.). Elles ne sont pas écrites par vous, prennent beaucoup de place, et peuvent être régénérées à tout moment à partir d'un fichier de configuration (composer.json, package.json...). Les versionner alourdirait inutilement le dépôt et créerait des risques de conflits ou d'incohérences entre environnements.

# 2. Différence entre un commit et un tag ?

Un commit est un instantané de l'état du projet à un moment donné, avec un message décrivant le changement. L'historique Git est une suite de commits.
Un tag est une étiquette pointant vers un commit précis, généralement utilisée pour marquer une version stable ou une release (ex : v0.0.0, v1.2.0). Contrairement à une branche, un tag ne bouge pas et ne "progresse" pas avec de nouveaux commits.

# 3. Pourquoi la branche main doit-elle rester stable ?
Parce qu'elle représente généralement la version de référence du projet, celle qui est censée fonctionner correctement (souvent celle déployée en production ou utilisée comme base par les autres développeurs). Si main contient du code instable ou cassé, cela peut bloquer toute l'équipe, casser des déploiements, ou introduire des bugs difficiles à tracer. C'est pourquoi on développe généralement sur des branches séparées et on ne fusionne dans main que du code testé et validé.


# 1. Pourquoi placer index.php dans un dossier public ?

Le dossier public est le seul dossier qui doit être accessible directement par le navigateur. On y place donc index.php, qui constitue le point d'entrée de l'application.

Cela permet de protéger les fichiers internes comme les classes métier, les fichiers de configuration et les accès à la base de données.

#2. Pourquoi toutes les requêtes devraient-elles passer par ce fichier ?

Parce que public/index.php joue le rôle de Front Controller.

Toutes les requêtes passent par un même point d'entrée, ce qui permet de centraliser :

le chargement automatique des classes ;
la résolution des URL ;
le contrôle des requêtes ;
l'appel des contrôleurs.

Navigateur
    ↓
public/index.php
    ↓
Routeur
    ↓
Controller
    ↓
Service
    ↓
Repository
    ↓
Base de données



# 3. Quels éléments ne devraient jamais se trouver dans le dossier public ?

Le dossier public ne doit pas contenir les éléments internes ou sensibles de l'application.

Il ne faut notamment pas y mettre directement :

les classes métier (Entity) ;
les Repository ;
les Service ;
les fichiers de configuration ;
les informations de connexion à PostgreSQL ;
les fichiers .env ;
les fichiers internes de l'application.

Par contre, on peut y trouver les éléments qui doivent être accessibles au navigateur, comme :

public/
├── index.php
├── css/
├── js/
└── images/


# 4. Comment avez-vous réparti les responsabilités entre vos dossiers ?

Nous avons séparé l'application en plusieurs dossiers afin que chaque partie ait une responsabilité précise.

Par exemple, pour enregistrer une copie :
Requête HTTP
     ↓
index.php
     ↓
CopieController
     ↓
CopieService
     ↓
CopieRepository
     ↓
PostgreSQL