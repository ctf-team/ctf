FROM resin/raspberrypi3-python

RUN mkdir /app

WORKDIR /app

COPY . ./

RUN rm ./solution.py

ENTRYPOINT ["python", "timeapi.py"]