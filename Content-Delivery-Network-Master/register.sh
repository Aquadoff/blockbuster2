#!/bin/bash
curl --header "Content-Type: application/json" --request POST --data '{"address":"192.168.1.117/download.php"}' http://video-server-router:8888/get-video-address.php
