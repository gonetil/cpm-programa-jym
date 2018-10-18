#!/bin/bash
# Enmascara la ejecucion de la consola de symfony dentro de un container Docker
# Desarrollado por @ezemanzur

CONTAINER="cpm-programa-jym_web_1"

COMANDO="app/console "
while [[ $# -gt 0 ]]
do
  key="$1"

  case $key in
-c | --command)
COMANDO=$2
shift
shift
;;
--container)
CONTAINER="$2"
shift
shift;;
*)
COMANDO=${COMANDO}$1
shift
;;
  esac
done

docker container exec -it ${CONTAINER}  ${COMANDO}
