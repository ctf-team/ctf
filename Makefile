build: clear restart-docker-compose

build-all: clear build-challenge-validator build-serverless-ctf build-rtlo restart-docker-compose

build-challenge-validator:
		@echo "Building challenge validator..."
		docker build -t challenge-validator -f docker/challenge-validator/Dockerfile .

build-serverless-ctf:
		@echo "Building serverless-ctf..."
		docker build -t serverless-ctf -f docker/serverless-ctf/Dockerfile .
# Attacks
build-rtlo:
		@echo "Building attacks-rtlo..."
		docker build -t attacks-rtlo -f docker/attacks/rtlo/Dockerfile .

# Compose

restart-docker-compose:
		@echo "Pulling down docker-compose..."
		docker-compose down
		@echo "Pulling up docker-compose..."
		docker-compose up -d

clear:
		@clear