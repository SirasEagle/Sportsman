~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#                        NOTES FOR BEGINNERs                        #
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

SETUP________________________________________________________________

    > start database        XAMPP->MySQL->Start  
    > start server          XAMPP->Apache->Start
    > start server          [project folder] symfony server:start
    > open http://localhost:8000/calendar


AUTOMATIC_GENERATION_________________________________________________

    > entity 1      php .\bin\console make:entity
    > entity 2      symfony console make:entity Name
    > controller    symfony console make:controller ConferenceController
    > form          symfony console make:form FormName
    > command       symfony console make:command CommandName


MIGRATIONS___________________________________________________________

    > create migration      php bin/console make:migration
    > create migration      php bin/console doctrine:migrations:diff
    > execute migration     php bin/console doctrine:migrations:migrate
    
    > "The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue."
        - visit https://github.com/doctrine/DoctrineMigrationsBundle/issues/337
        - vendor\doctrine\migrations\lib\Doctrine\Migrations\Metadata\Storage\TableMetadataStorage.php
    > show generated statements in console
                            php bin/console doctrine:schema:update --dump-sql 
    > mark all migrations as not executed (use with caution)
        php bin/console doctrine:migrations:version --delete --all
    > mark all migrations as executed
        php bin/console doctrine:migrations:version --add --all


TIPS_________________________________________________________________

Twig-ausgabe:     {{ dump(unit) }}
Symfony-ausgabe:     dump($...)
Symfony-Version:  php bin/console --version

STRUCTURE____________________________________________________________

database: http://localhost/phpmyadmin/

[config > routes > attributes.yaml]
Configuration to tell Symfony to look for routes defined as
attributes in any PHP class stored in the e.g.:
.../src/Controller/ directory.

[config > routes.yaml]
Connection between a path and the function to call (; page to load)
from there.

[src > Controller > XyzController.php]
Contains the functions that are calles, when the path is used.
Path are found in the routes.yaml, or can be noted as
annotations.

[templates > dirXY > fileXY.html.twig]
The file that is called from a Controller function and loaded
as a page.




--------

SELECT w.*
FROM workout w
LEFT JOIN unit u ON w.id = u.workout_id
WHERE u.workout_id IS NULL;


DELETE FROM workout
WHERE id IN (
    SELECT w.id
    FROM workout w
    LEFT JOIN unit u ON w.id = u.workout_id
    WHERE u.workout_id IS NULL
);