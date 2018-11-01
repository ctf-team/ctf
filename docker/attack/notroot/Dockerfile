FROM resin/raspberrypi3-alpine:latest
RUN apk --no-cache add openssh-server
RUN adduser -D -u 1000 notroot
RUN mkdir /root/ && echo "FLAG---" > /root/flag.txt
RUN chmod 0600 /root/flag.txt
ENTRYPOINT ["su notroot /bin/bash"]