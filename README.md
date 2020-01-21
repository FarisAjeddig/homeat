tcafe
=====

A Symfony project created on November 17, 2018, 10:42 pm.
!!!! Lorsque l'on vide le cache, bien penser à remettre les droits d'écriture sur le cache de JMS Serializer, sinon l'API ne fonctionne plus. La commande est la suivante : !!!!
chmod 777 ./var/cache/prod/jms_serializer/
---
Pour modifier les vues : 
* Dans le cas des vues relatives à la gestion de son compte (authentification, inscription, gestion du profil ...) : Aller dans app/Resources/FOSUserBundle/views.
    * Les vues correspondant au login sont dans "Security".
    * Les vues correspondant à l'inscription sont dans "Registration".
    * Les vues correspondant à la gestion du profil sont dans "Profile".
* Pour les vues administrateurs, il faut aller dans app/Resources/views/admin, sauf pour le menu qui correspond au fichier app/Resources/views/layoutadmin.html.twig.
* Pour les vues utilisateurs, il faut aller dans app/Resources/views/default, sauf pour le menu qui correspond au fichier app/Resources/views/base.html.twig.


---
Pour lancer l'application
- composer install
- php bin/console doctrine:schema:update --force
- php bin/console server:start

Eventuellement :
- php bin/console doctrine:database:create

Pour mettre à jour le front lorsqu'il y a une modification des ressources assetic :
- php bin/console assets:install --symlink
- php bin/console assetic:dump --env=dev
- php bin/console cache:clear --env=dev

En production, il faut également réimporter le dossier "fosceditor" dans web/bundles/
- scp -r web/bundles/fosckeditor/ root@51.77.211.74:/var/www/tcafe/web/bundles/fosckeditor/

Pour créer un administrateur, passez d'abord par le site et créez un compte. Ensuite, à la racine du projet (tcafe/), executez la commande suivante :
`php bin/console fos:user:promote`
La console vous demande alors d'entrer le `username` de l'utilisateur, entrez celui que vous venez de créer. Enfin, elle vous demande le rôle à ajouter. C'est le suivant :
`ROLE_ADMIN`.
# homeat
