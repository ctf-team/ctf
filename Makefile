build:
		@echo "Nothing to build."

rebuild: clear restart-docker-compose

build-all: clear challenge-validator serverless-ctf attack-rtlo reverse-reverse1 restart-docker-compose

challenge-validator:
		@echo "Building challenge validator..."
		@docker build -t challenge-validator -f docker/challenge-validator/Dockerfile .
		@docker-compose up --no-deps -d challenge-validator

serverless-ctf:
		@echo "Building serverless-ctf..."
		@docker build -t serverless-ctf -f docker/serverless-ctf/Dockerfile .
		@docker-compose up --no-deps -d serverless-ctf

# Attacks
attack-rtlo:
		@echo "Building attack-rtlo..."
		@docker build -t attack-rtlo -f docker/attack/rtlo/Dockerfile .
		@docker-compose up --no-deps -d attack-rtlo

# Reverse
reverse-reverse1:
		@echo "Building reverse-reverse1..."
		@docker build -t reverse-reverse1 -f docker/reverse/reverse-1/Dockerfile .
		@docker-compose up --no-deps -d reverse-reverse1

# Compose
restart-docker-compose:
		@echo "Pulling down docker-compose..."
		@docker-compose down
		@echo "Pulling up docker-compose..."
		@docker-compose up -d

clear:
		@clear