build: clear build-challenge-validator build-serverless-ctf restart-docker-compose

build-challenge-validator:
		@echo "Building challenge validator..."
		docker build -t challenge-validator -f docker/challenge-validator/Dockerfile .

build-serverless-ctf:
		@echo "Building serverless-ctf..."
		docker build -t serverless-ctf -f docker/serverless-ctf/Dockerfile .

restart-docker-compose:
		@echo "Pulling down docker-compose..."
		docker-compose down
		@echo "Pulling up docker-compose..."
		docker-compose up -d

clear:
		@clear