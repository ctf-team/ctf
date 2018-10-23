
FROM golang:latest AS builder

# Download and install the latest release of dep
#ADD https://github.com/golang/dep/releases/download/v0.4.1/dep-linux-amd64 /usr/bin/dep
#RUN chmod +x /usr/bin/dep

# Copy the code from the host and compile it
WORKDIR $GOPATH/src/ctf
#COPY ../Gopkg.toml ../Gopkg.lock ./
#RUN dep ensure --vendor-only
COPY ../ ./
RUN CGO_ENABLED=0 GOOS=linux go build -a -installsuffix nocgo -o /app ctf/cmd/test

FROM scratch
FROM alpine:latest
RUN apk --no-cache add ca-certificates && update-ca-certificates
RUN apk --no-cache add tzdata
COPY --from=builder /app ./
ENTRYPOINT ["./app"]