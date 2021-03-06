# EcoSystems

Monitoring system made with Arduino to monitor an aquaponic system. This project is named EcoSystems for the simple fact that an aquaponic system is a small ecosystem and, as all ecosystems, it's important to keep an eye on it.

An ecosystem is a community of living organisms (plants, animals and microbes) in conjunction with the nonliving components of their environment (things like air, water and mineral soil), interacting as a system.
http://en.wikipedia.org/wiki/Ecosystem

Arduino code: https://github.com/eoto88/Arduino-EcoSystem

## This project is in development and the code here is not complete yet!
For more infos, come visit later or simply write me!

## Installation
1. Create directory application/cache and it must be writable.
2. Create directory application/logs and it must be writable.
3. Import the database structure in MySQL
4. Rename the file application/config/database.template.php to database.php and add your database configurations.
5. Rename template.htaccess to .htaccess
6. Add cron tasks
    * [ 0 6 * * 0 ] php index.php --task=Cron --action=backupLastDays
    * [ 0 5 * * * ] php index.php --task=Cron --action=checkTodos

7. Rename /assets/js/config.template.js to config.js and change for you firebase configs

### bootstrap.php
* SALT
*

To configure the web application, you need to copy the application/config*.template.php to application/config/*.php

Ex: database.template.php => database.php

## API

Additionnal fields
[addFields=params,data]

Filters
[filters=header:eq:1]

* /api/instances
* /api/instances/:id
* /api/instances/:id/groups
* /api/instances/{0}/groups
* /api/instances/{0}/groups/{1}
* /api/instances/{0}/params
* /api/instances/{0}/params/{1}
* /api/instances/{0}/params/{1}/data
* /api/instances/{0}/params/{1}/data/{2}