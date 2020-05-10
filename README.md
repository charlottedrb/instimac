# imac1-web-projet

## Configurer le projet

- Je me place dans ma branche
- Mise à jour des logins de votre base de données dans `src/Database/Database.php`
```

```
- Test en appelant `/init.php` si OK, continue.

## Avant de merge sur la branche dev **uniquement**

- Vérifier que les logins de la base de données soient vide = ''
- Fichiers inutiles supprimés
- je tire les autres modification avec `git fetch`
- Si j'ai modifié = je commit à nouveau

## Comment ça marche ? Qui fait quoi ?

![Graphique de git](http://courses.gregoirep.com/projects/git/illu-site-web-requetes.png)
![Graphique de git](http://courses.gregoirep.com/projects/git/illu-site-web-requetes-team.png)

## Rappel git flow

![Graphique de git](http://courses.gregoirep.com/projects/git/git_flow_illustration.png)

## Rappel git commandes dans l'ordre d'utilisation

### Comment marche git ?

![Graphique de git](https://www.sebastien-gandossi.fr/user/pages/03.blog/13.difference-entre-git-reset-et-git-rm-cached/difference-entre-git-reset-et-git-rm-cached.jpg)

### Commandes majeures

`origin` est le nom de notre depot distant
`mabranche` est la branche de travail actuel

`git branch nouvellebranche` : crée une nouvelle branche

`git checkout -b nouvellebranche` : crée une nouvelle branche et vous place dans celle-ci

`git pull origin` : tirer les modification du repo distant + merge dans la branche courante les modifications, cette commande équivaut à :
- `git fetch origin`
- `git merge branchesModifiéesInferieure mabranche`

`git add fichier` : suivre un fichier ou un dossier (stagged)

`git rm --cached` : retirer un fichier ou un dossier du suivi (non stagged)

`git status` : Voir les modification "stagged" en cours 

` git commit -m "mon message" ` : Faire un commit, paramètre -m pour le message, -a pour ajouter tous les fichiers non suivis

`git push origin mabranche` : pousser les modification sur le repot distant
