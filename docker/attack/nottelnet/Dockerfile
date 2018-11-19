FROM rpi-golang-builder:latest AS builder

RUN apk add --no-cache --virtual build-dependencies build-base gcc wget git make

RUN mkdir /app
WORKDIR /app
COPY ./cmd/attack/nottelnet/ ./

RUN gcc *.c -o /app/app

FROM scratch
FROM resin/raspberrypi3-alpine:latest

RUN apk add --no-cache sudo

RUN adduser -h /home/dvr -s /bin/bash -D dvr 
RUN echo 'dvr:1lKoeK9q@$3P7i9W$TyEnqByxuw7dJa@' | chpasswd

RUN echo 'dvr ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers

RUN mkdir -p /app/recordings
WORKDIR /app
COPY --from=builder /app/app ./
RUN chmod +x ./app

RUN echo -e "admin:$dahua$4WzwxXxM\n" > /app/auth.bin

ENTRYPOINT ["./app"]