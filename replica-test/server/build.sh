#!/bin/bash

cd "$(dirname "$0")"

# Request root privilege
[ "$UID" -eq 0 ] || exec sudo "$0" "$@"

# Maven build
mvn clean:clean compiler:compile jar:jar
if [ $? -ne 0 ]
then
    echo "Maven build failed!"
    exit 1
else
    echo ""
fi

# Docker build
docker build -t cdp1-kube-replica-server .
