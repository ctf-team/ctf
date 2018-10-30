#!/bin/bash

docker build -t challenge-validator -f docker/challenge-validator/Dockerfile .
docker build -t serverless-ctf -f docker/serverless-ctf/Dockerfile .
#docker build -t test-image -f docker/Dockerfile .

docker-compose down
docker-compose up -d