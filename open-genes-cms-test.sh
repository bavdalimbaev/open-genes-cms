#!/bin/sh
CONTAINER_NAME=OPENGENESCMSTESTDB
NETWORK_NAME=$CONTAINER_NAME-net

EXISTING_CONTAINER=`docker ps -q -f "name=$CONTAINER_NAME"`

uid=$(id -u)
gid=$(id -g)
OPEN_GENES_UID=$uid:$gid

if [ "$1" = "db" -a "$2" = "status" ]
then
	echo "db container $EXISTING_CONTAINER"
	echo "db network $NETWORK_NAME"
	exit

fi

if [ "$1" = "db" -a "$2" = "up" ]
then
	[ "$EXISTING_CONTAINER" != "" ] && echo "db is already running in container $EXISTING_CONTAINER" && exit
	docker network create $NETWORK_NAME
	docker run --volume `pwd`/docker/mysql/dump.sql:/docker-entrypoint-initdb.d/init.sql --volume `pwd`/docker/mysql/charset.cnf:/etc/mysql/conf.d/charset.cnf --network $NETWORK_NAME -p 3308:3306 -e MYSQL_ROOT_PASSWORD=secret -d --name $CONTAINER_NAME -it mysql:5.7 --init-file /docker-entrypoint-initdb.d/init.sql
	php dbwait.php 127.0.0.1 3308 root secret open_genes 30 || exit
	docker run --user $OPEN_GENES_UID --volume `pwd`/app:/var/www --volume `pwd`/env-test:/var/www/.env --network $NETWORK_NAME -e DB_HOST=$CONTAINER_NAME -e DB_USER=root -e DB_PASS=secret -e "DB_DSN=mysql:host=$CONTAINER_NAME;dbname=open_genes" -it cms_cms php //var/www/console/yii.php migrate --interactive=0
	exit
fi

if [ "$1" = "db" -a "$2" = "down" ]
then
	[ "$EXISTING_CONTAINER" = "" ] && echo "no db is running" && exit
	docker stop $EXISTING_CONTAINER
	docker rm $EXISTING_CONTAINER
	docker network rm $NETWORK_NAME
	exit
fi

if [ "$1" = "db" -a "$2" != "up" -a "$2" != 'down' -a "$2" != 'status' ]
then
	echo "Usage: $0 db [up|down|status]"
	exit
fi

[ "$EXISTING_CONTAINER" = "" ] && echo "no test db is running, start it with \"$0 db up\" first" && exit

ECHOCMD=""
[ "$1" = "echo" ] && ECHOCMD=echo
$ECHOCMD docker run --user $OPEN_GENES_UID --volume `pwd`/app:/var/www --volume `pwd`/env-test:/var/www/.env --network $NETWORK_NAME -e DB_HOST=$CONTAINER_NAME -e DB_USER=root -e DB_PASS=secret -e "DB_DSN=mysql:host=$CONTAINER_NAME;dbname=open_genes" -it cms_cms php vendor/bin/codecept run
