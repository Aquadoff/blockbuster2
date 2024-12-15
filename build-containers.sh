#!/bin/bash
docker-compose up -d --no-deps --build 
curl --header "Content-Type: application/json" --request POST --data \
'{"address":"192.168.1.117:8088/download.php"}' http://127.0.0.1:8888/get-video-address.php
echo " "
