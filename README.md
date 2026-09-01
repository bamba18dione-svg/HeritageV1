# HeritageV1

# 1 . Pourquoi /vendor ne doit-il pas être versionné ?

Ce dossier contient généralement des dépendances externes (bibliothèques tierces installées automatiquement, par exemple via Composer, npm, etc.). Elles ne sont pas écrites par vous, prennent beaucoup de place, et peuvent être régénérées à tout moment à partir d'un fichier de configuration (composer.json, package.json...). Les versionner alourdirait inutilement le dépôt et créerait des risques de conflits ou d'incohérences entre environnements.

# 2. Différence entre un commit et un tag ?

Un commit est un instantané de l'état du projet à un moment donné, avec un message décrivant le changement. L'historique Git est une suite de commits.
Un tag est une étiquette pointant vers un commit précis, généralement utilisée pour marquer une version stable ou une release (ex : v0.0.0, v1.2.0). Contrairement à une branche, un tag ne bouge pas et ne "progresse" pas avec de nouveaux commits.

# 3. Pourquoi la branche main doit-elle rester stable ?
Parce qu'elle représente généralement la version de référence du projet, celle qui est censée fonctionner correctement (souvent celle déployée en production ou utilisée comme base par les autres développeurs). Si main contient du code instable ou cassé, cela peut bloquer toute l'équipe, casser des déploiements, ou introduire des bugs difficiles à tracer. C'est pourquoi on développe généralement sur des branches séparées et on ne fusionne dans main que du code testé et validé.