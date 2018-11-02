build:
		@echo "Nothing to build."

rebuild: clear restart

build-all: clear attack-rtlo reverse-reverse1 restart

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

# Web
web:
		@echo "Building web..."
		@docker-compose up --no-deps -d hackme hackme-php

# Compose
restart:
		@echo "Pulling down docker-compose..."
		@docker-compose down
		@echo "Pulling up docker-compose..."
		@docker-compose up -d

clear:
		@clear