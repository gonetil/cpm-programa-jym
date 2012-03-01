#!/bin/sh

set -e

dbname="jym_jovenes"
dbuser="root"
dbpass="root"

case $1 in
        cc)
		echo ">>>>>Vaciando la cache ..."
        	rm -fr app/cache/dev* app/cache/prod*
		chmod -R 777 app/cache
		exit 0;
        ;;
        initdb)
        	rm -fr app/cache/dev* app/cache/prod*
		echo ">>>>>Regenerando el schema de la base de datos"
        	php app/console doctrine:schema:drop --force
        	php app/console doctrine:schema:create
		
		echo ">>>>>Generando las entidades"
		php app/console doctrine:generate:entities Cpm

		echo ">>>>>Cargando datos inciales"
        	mysql -u$dbpass -p$dbpass $dbname < app/sql/pidu.sql

		 rm -fr app/cache/dev* app/cache/prod*

		exit 0;
        ;;
	*)
        	echo "Usage: ($0 action), donde action es cc, initdb"
		exit 1
        ;;
esac




