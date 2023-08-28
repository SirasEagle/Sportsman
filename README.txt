~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#                        NOTES FOR BEGINNERs                        #
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

SETUP________________________________________________________________
XAMPP: start Apache and MySQL
To start the Server, navigate into the project folder and type:
[symfony server:start]

php .\bin\console //for help


Entity generieren____________________________________________________

php .\bin\console make:entity 
php bin/console make:migration
php bin/console doctrine:migrations:migrate

Controller__________________

symfony console make:controller ConferenceController

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