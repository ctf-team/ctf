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
  
For all of the golang-based applications, you'll also need to remove the following content from each Dockerfile:
`GOARCH=arm GOARM=7`
  
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

For `CrackMe` and `PatchMe`, you'll have to manually compile the challenges with `gcc`, as these challenges are done by passing the file around only.

```
# CrackMe
cd cmd/reverse/crackme
gcc main.c -o crackme

# PatchMe
cd cmd/reverse/patchme
gcc main.c -o patchme
```

One other thing you'll need to do is set up CTFd. This is pretty easy to do. The challenges defined by port in docker-compose.yml so it's pretty easy to see what needs added to the description for your CTFd challenge items.

## Challenges Included

* Pwn
  * TimedApi - A time-based API written in Python.
  * NotTelnet - A minimal chinese DVR-type challenge over telnet written in C.
  * DropZone - A media upload site that doesn't properly validate uploads written in PHP.
* Web
  * Login - A super basic login prompt written in PHP.
  * GetMe - A super basic authorize check written in PHP.
  * HackMe - A progressional site that has multiple guided steps to get your way to editing the site written in PHP.
  * SetMe - A cookie based auth written in PHP.
  * MD5 - A basic MD5-based authentication written in PHP.
  * MD5-2 - A fun MD5-based challenge written in PHP.
  * Robots - A hidden directory challenge written in PHP.
  * Wall of Fire - A "firewall" challenge that deals with HTTP headers, written in PHP.
  * Pager - A file browser challenge written in PHP.
  * Explorer - A hidden directory challenge that involves logging into a server over SSH after finding the hidden file.
* Reverse Engineering
  * CrackMe - A super basic reverse engineering challenge written in C.
  * PatchMe - A XOR-based reverse engineering challenge written in C.
  * Reverse-1 - A buffer-overflow based challenge written in C and Golang.
* Encoding
  * RTLO - A Right-To-Left encoding challenge written in Golang.

There's also a challenge called entrypoint that was never finished. It was going to be written in C# but then I ran out of time.
