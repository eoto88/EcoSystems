# EcoSystem

Monitoring system made with Arduino to monitor an aquaponic system. This project is named EcoSystem for the simple fact that an aquaponic system is a small ecosystem and, as all ecosystems, it's important to keep an eye on it.

An ecosystem is a community of living organisms (plants, animals and microbes) in conjunction with the nonliving components of their environment (things like air, water and mineral soil), interacting as a system.
http://en.wikipedia.org/wiki/Ecosystem

Components
- Arduino Uno/Arduino Mega
- Ethernet Shield
- Photoresistor
- Thermal sensor DS18B20
- Real time clock DS1307
- 4 channel relay module
- Temperature Humidity Sensor AM2301 (Soon)

:fish: + :sunny: + :herb: = :tomato:

## This project is in development and the code here is not complete yet!
For more infos, come visit later or simply write me!

## Installation
1. Create directory application/cache and it must be writable.
2. Create directory application/logs and it must be writable.
3. Import the database structure in MySQL
4. Rename the file application/config/database.template.php to database.php and add your database configurations.
5. Rename template.htaccess to .htaccess

### bootstrap.php
* SALT
* 

Rename template.htaccess to .htaccess

To configure the web application, you need to copy the application/config*.template.php to application/config/*.php

Ex: database.template.php => database.php
