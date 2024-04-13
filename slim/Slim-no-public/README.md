Configuration slim 4.
=====================

* Serveur web en  proxy inverse. nginx et apache pour être complet sur la démo.
* Un serveur MariaDb. 
* Et adminer (:->) et phpmyadmin (|-<)

**AUCUN** fichier php n'est accessible par les serveurs web.  Seules les url NON
interdites et  ne correspondant pas à  des fichiers existants ou  à des patterns
interdits (ex \.php) sont forwardées sur le container php-fpm via fast-cgi.

.\
├── app  Document root pour nginx, apache ET php-fpm (=> portée de php composer)\
│   ├── composer.json\
│   ├── composer.lock\
│   ├── config\
│   │   └── config.php\
│   ├── css\
│   │   └── style.css\
│   ├── images\
│   │   └── cm.png\
│   ├── index.php **ATTENTION** directerment ICI car ainsi configuré dans le forwarding fast-cgi des proxy server http\
│   ├── src racine du top level name space pour php PSR-4\
│   │   ├── Controllers\
│   │   ├── Handlers\
│   │   ├── Middlewares\
│   │   └── Services\
│   ├── templates\
│   │   ├── base.html\
│   │   └── test.html\
│   └── vendor\
│       ├── autoload.php\
│       └── ...\
├── dockerfiles\
│   ├── .env Y METTRE à COMPOSE_PROJECT_NAME\
│   ├── apache2.conf\
│   ├── docker-compose.yml\
│   ├── docker-php-ext-xdebug.ini\
│   ├── fpm.ini\
│   ├── mon_php.docker\
│   ├── nginxconf\
│   │   └── default.conf\
│   └── trash\
├── jsperf.sql\
├── load-db.sh\
└── logs\
    ├── apache\
    └── nginx\
        ├── access.log\
        └── error.log
