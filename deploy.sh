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

NGINX_CONF="run/nginx/nginx.conf"
if [ -f "$NGINX_CONF" ]; then
    CERT_PATH=$(grep "ssl_certificate " $NGINX_CONF | head -1 | awk '{print $2}' | tr -d ';')
    CERT_DIR=$(dirname "$CERT_PATH")
    
    echo -e "${BLUE}Verificando certificado em: $CERT_DIR...${RESET}"

    if ! $DOCKER_CMD -f run/docker-compose.prod.yml run --rm --entrypoint "ls $CERT_PATH" certbot > /dev/null 2>&1; then
        echo -e "${YELLOW}⚠️  Certificado não encontrado. Gerando DUMMY via Certbot container...${RESET}"
        
        $DOCKER_CMD -f run/docker-compose.prod.yml run --rm --entrypoint "sh -c" certbot "\
            apk add --no-cache openssl && \
            mkdir -p $CERT_DIR && \
            openssl req -x509 -nodes -newkey rsa:4096 -days 1 \
                -keyout $CERT_DIR/privkey.pem \
                -out $CERT_DIR/fullchain.pem \
                -subj '/CN=localhost'"
                
        echo -e "${GREEN}Dummy certificate criado com sucesso!${RESET}"
    fi
else
    echo -e "${RED}Erro: Nginx conf não encontrado.${RESET}"
fi

echo -e "${YELLOW}Subindo containers...${RESET}"
$DOCKER_CMD -f run/docker-compose.prod.yml up -d --build

echo -e "${YELLOW}Solicitando certificado Let's Encrypt...${RESET}"
$DOCKER_CMD -f run/docker-compose.prod.yml up certbot
$DOCKER_CMD -f run/docker-compose.prod.yml exec nginx nginx -s reload

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