# ppeSymfony

# <ins>[[PPE-SYMFONY](https://github.com/nxmad3/ppeSymfony.git)]

## Information :

### Goal : 

Create a rental management platform.

### Used technologies: 

[SYMFONY](https://symfony.com/) | [HTML](https://developer.mozilla.org/fr/docs/Web/HTML) | [CSS](https://developer.mozilla.org/fr/docs/Web/CSS) | [PHP](https://www.php.net/) | [BOOTSTRAP](https://getbootstrap.com/) | [JavaScripts](https://developer.mozilla.org/fr/docs/Web/JavaScript) | [MySQL](https://www.mysql.com/fr/)

### Realization period: 

End of December 2021 to April 25, 2022.

## How to launch the project ?

First type this command to install and build the project

        npm install
        npm run build
        composer install

If the database wasn't create run 

        php bin/console doctrine:database:create

To fill the database run

        php bin/console doctrine:schema:update --dump-sql --force
        php bin/console doctrine:fixtures:load

After making sure that the database has been filled and the project has been built you can type

        symfony server:start
