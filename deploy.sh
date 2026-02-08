#!/bin/bash

GREEN='\033[01;32m'
YELLOW='\033[01;33m'
BLUE='\033[01;34m'
RED='\033[01;31m'
RESET='\033[0m'

echo -e "${GREEN}🚀 Iniciando Deploy de Produção...${RESET}"

if docker compose version > /dev/null 2>&1; then
    DOCKER_CMD="docker compose"
elif command -v docker-compose > /dev/null 2>&1; then
    DOCKER_CMD="docker-compose"
else
    echo -e "${RED}ERRO: Docker Compose não encontrado.${RESET}"
    exit 1
fi

echo -e "${YELLOW}Configurando arquivos de ambiente...${RESET}"
if [ -f "run/.env" ]; then
    cp run/.env .env
    echo -e "${BLUE}- .env atualizado a partir de run/.env${RESET}"
elif [ ! -f ".env" ] && [ -f "run/.env.example" ]; then
    cp run/.env.example .env
    echo -e "${BLUE}- .env criado a partir de run/.env.example${RESET}"
fi

if [ -f .env ]; then export $(grep -v '^#' .env | xargs); fi

echo -e "${YELLOW}Subindo containers de produção...${RESET}"
$DOCKER_CMD -f run/docker-compose.prod.yml up -d --build

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erro no build. Abortando.${RESET}"
    exit 1
fi

echo -ne "${BLUE}Aguardando Postgres ficar pronto...${RESET}"
CONTAINER_DB_NAME="cawe_blog_db"
DB_USER=${DB_USERNAME:-cawe}
DB_NAME=${DB_DATABASE:-laravel}

until docker exec $CONTAINER_DB_NAME pg_isready -U "$DB_USER" -d "$DB_NAME" > /dev/null 2>&1; do
  echo -n "."
  sleep 1
done
echo -e " ${GREEN}Pronto!${RESET}"

echo -e "${YELLOW}Finalizando setup do Laravel...${RESET}"
docker exec cawe_blog_app composer install --no-dev --optimize-autoloader
docker exec cawe_blog_app php artisan key:generate
docker exec cawe_blog_app php artisan migrate --force
docker exec cawe_blog_app php artisan filament:assets
docker exec cawe_blog_app php artisan view:clear
docker exec cawe_blog_app php artisan config:clear
docker exec cawe_blog_app php artisan config:cache

echo -e "${GREEN}✅ Deploy finalizado com sucesso!${RESET}"
