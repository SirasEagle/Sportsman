~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#                        NOTES FOR BEGINNERs                        #
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

SETUP________________________________________________________________
> XAMPP: start Apache and MySQL
> To start the Server, navigate into the project folder and type:
    symfony server:start
> open http://localhost:8000/calendar
> for help:
    .\bin\console


AUTOMATIC_GENERATION_________________________________________________

    > entity 1      php .\bin\console make:entity
    > entity 2      symfony console make:entity Name
    > controller    symfony console make:controller ConferenceController
    > form          symfony console make:form FormName
    > command       symfony console make:command CommandName


MIGRATIONS___________________________________________________________

> php bin/console make:migration
> php bin/console doctrine:migrations:migrate
> mark all migrations as not executed (use with caution)
    php bin/console doctrine:migrations:version --delete --all


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