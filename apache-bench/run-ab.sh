#!/bin/bash
cd "$(dirname "$0")"

SERVER_IP=$1
POOL_NUM=$2
FILE_NAME=$3

ab -c $2 -n 200 -g $FILE_NAME http://${SERVER_IP}/
