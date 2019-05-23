# ctf

## Prerequisites
All of this was built on a Raspberry Pi 3, so it runs flawlessly on one (except for CTFd, you'll need to pull your own image and modify the Dockerfile and rebuild it.

To do so, cd into the "build" folder and run the following.

*For CTFd:*
```
cd CTFd
docker build -t ctfd .
```

*For rpi-docker-golang:*
```
cd rpi-docker-golang
docker build -t rpi-docker-golang .
```

## If you don't intend on running this on a Raspberry Pi

* In ALL Dockerfiles (located in the docker folder), change the image to the appropriate x86-equivalent one:
  * attack/nottelnet:   `FROM golang:latest AS builder` on line 1, `FROM alpine:latest AS builder` on line 12.
  * attack/rtlo:        `FROM golang:latest AS builder` on line 1, `FROM alpine:latest AS builder` on line 16.
  * attack/timeapi:     `FROM python:latest`
  * reverse/reverse-1:  `FROM golang:latest as builder` on line 1, `FROM alpine:latest AS builder` on line 16.
  
* In the docker-compose.yml file, make the following changes:
  * explorer image:     `alpine`
  * hackme image:       `nginx`
  * hackme-php image:   `php/7.1-fpm-alpine3.8`
  * hackme-mysql image: `mariadb`
  * breakme-web image:  `nginx`
  * breakme-php image:  `php/7.1-fpm-alpine3.8`
  * pager-web image:    `nginx`
  * pager-php image:    `php/7.1-fpm-alpine3.8`

## Setup

It's as simple as:

`docker-compose up -d`

Check docker-compose.yml for all the challenges and the possible options. I would also recommend changing any flags where you see fit.

One other thing you'll need to do is set up CTFd. This is pretty easy to do. The challenges defined by port in docker-compose.yml so it's pretty easy to see what needs added to the description for your CTFd challenge items.
