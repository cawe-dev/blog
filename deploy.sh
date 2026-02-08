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
    DOCKER_CMD="docker compose"
else
    echo -e "${RED}ERRO: Docker Compose não encontrado.${RESET}"
    exit 1
fi

if [ -f "run/.env" ]; then
    cp run/.env .env
fi

echo -e "${YELLOW}Subindo containers...${RESET}"
$DOCKER_CMD -f run/docker-compose.prod.yml up -d --build

echo -e "${BLUE}Configurando permissões...${RESET}"
docker exec -u root cawe_blog_app mkdir -p public/js/filament/plugins
docker exec -u root cawe_blog_app chown -R www:www /var/www/application

echo -e "${YELLOW}Compilando assets (Vite) dentro do container...${RESET}"
docker exec cawe_blog_app npm install
docker exec cawe_blog_app npm run build

echo -e "${YELLOW}Finalizando configuração do Laravel...${RESET}"
docker exec cawe_blog_app composer install --no-dev --optimize-autoloader
docker exec cawe_blog_app php artisan migrate --force

docker exec cawe_blog_app php artisan key:generate
docker exec cawe_blog_app php artisan filament:assets
docker exec cawe_blog_app php artisan optimize
docker exec cawe_blog_app php artisan optimize:clear
docker exec cawe_blog_app php artisan filament:optimize
docker exec cawe_blog_app php artisan filament:optimize-clear
docker exec cawe_blog_app php artisan view:clear
docker exec cawe_blog_app php artisan config:clear
docker exec cawe_blog_app php artisan config:cache
docker exec cawe_blog_app php artisan route:cache
docker exec cawe_blog_app php artisan view:cache


echo -e "${GREEN}✅ Deploy finalizado com sucesso!${RESET}"