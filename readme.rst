###################
Objectif
###################

Développer les API REST suivantes : 

- Récupération d’une liste paginée d’utilisateurs (potentiellement 5M d’utilisateurs)
- Création d’un utilisateur
- Modification d’un utilisateur
- Suppression d’un utilisateur

Un utilisateur ayant les infos suivantes : un ID unique, un prénom, un nom, une adresse
email, un numéro de téléphone, une adresse postale, un statut professionnel et une date de
dernière connexion.

L’inscription et la modification doivent être possibles via des API publiques Front Office.
Le listing d’utilisateurs, leur modification et leur suppression doivent être possibles via des
API privées Back Office.

Pas besoin de gérer la sécurité ou l’authentification.
Développer un batch/cron permettant de supprimer régulièrement les utilisateurs qui ne se
sont pas connectés dans les 36 mois.


###################
Contraintes
###################

PHP8, CodeIgniter 3, MySQL
Utilisation du QueryBuilder de CI3 pour le modèle
Privilégier la qualité du code et les bonnes pratiques

###################
Rendu
###################

- Rendu du code : repo GIT ou un ZIP. Une démo hébergée est un plus. Intégrer les requêtes SQL.
- Une documentation technique expliquant l’utilisation de l’API et éventuellement les différents choix de conception.
- Une estimation du temps passé sur les différents aspects.
