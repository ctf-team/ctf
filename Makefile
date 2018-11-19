build: clear git build-all

rebuild: clear restart

build-all: clear nottelnet attack-rtlo reverse-reverse1 restart

# Nottelnet
nottelnet:
		@echo "Building nottelnet..."
		@docker build -t nottelnet -f docker/attack/nottelnet/Dockerfile .

# Attacks
attack-rtlo:
		@echo "Building attack-rtlo..."
		@docker build -t attack-rtlo -f docker/attack/rtlo/Dockerfile .

# Reverse
reverse-reverse1:
		@echo "Building reverse-reverse1..."
		@docker build -t reverse-reverse1 -f docker/reverse/reverse-1/Dockerfile .
		
update:
		@echo "Updating containers..."
		@docker-compose up --no-deps -d

# Compose
restart:
		@echo "Pulling down docker-compose..."
		@docker-compose down
		@echo "Pulling up docker-compose..."
		@docker-compose up -d

clear:
		@clear
		
git:
		@git reset --hard
		@git pull