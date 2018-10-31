build: clear restart-docker-compose

build-all: clear build-challenge-validator build-serverless-ctf build-rtlo restart-docker-compose

build-challenge-validator:
		@echo "Building challenge validator..."
		docker build -t challenge-validator -f docker/challenge-validator/Dockerfile .
		docker-compose up --no-deps -d challenge-validator

build-serverless-ctf:
		@echo "Building serverless-ctf..."
		docker build -t serverless-ctf -f docker/serverless-ctf/Dockerfile .
		docker-compose up --no-deps -d serverless-ctf

# Attacks
attack-rtlo:
		@echo "Building attack-rtlo..."
		docker build -t attack-rtlo -f docker/attack/rtlo/Dockerfile .
		docker-compose up --no-deps -d attack-rtlo

# Compose
restart-docker-compose:
		@echo "Pulling down docker-compose..."
		docker-compose down
		@echo "Pulling up docker-compose..."
		docker-compose up -d

clear:
		@clear