FROM resin/raspberrypi3-python

RUN mkdir /app

WORKDIR /app

COPY ./cmd/attack/timeapi/ ./

RUN rm ./solution.py

ENTRYPOINT ["python", "timeapi.py"]