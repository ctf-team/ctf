FROM rpi-golang-builder:latest AS builder

RUN apk add --no-cache git make gcc
RUN go get -u github.com/golang/dep/cmd/dep

ENV PATH $PATH:$GOPATH/bin

# Copy the code from the host and compile it
WORKDIR $GOPATH/src/ctf
COPY ./Gopkg.toml ./Gopkg.lock ./
RUN dep ensure --vendor-only
COPY . ./
RUN CGO_ENABLED=1 GOARCH=arm GOARM=7 GOOS=linux go build -a -o /app ctf/cmd/test

FROM scratch
FROM resin/raspberrypi3-alpine:latest
RUN apk --no-cache add ca-certificates && update-ca-certificates
RUN apk --no-cache add tzdata
COPY --from=builder /app ./
RUN chmod +x ./app
ENTRYPOINT ["/bin/sh"]