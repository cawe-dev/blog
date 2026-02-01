#!/bin/bash

GREEN='\033[01;32m'
YELLOW='\033[01;33m'
BLUE='\033[01;34m'
RED='\033[01;31m'
RESET='\033[0m'

cd "$(dirname "$0")"

if docker compose version > /dev/null 2>&1; then
    DOCKER_CMD="docker compose"
elif command -v docker-compose > /dev/null 2>&1; then
    DOCKER_CMD="docker-compose"
else
    echo -e "${RED}ERRO: Docker Compose não encontrado.${RESET}"
    exit 1
fi

echo -e "${GREEN}### Sync de Ambiente: Laravel + Docker Infra ###${RESET}"
echo -e "${BLUE}Usando comando: $DOCKER_CMD${RESET}\n"

# ==============================================================================
# 3. Sincronização Inteligente do .env
# ==============================================================================
echo -e "${YELLOW}1. Configurando variáveis de ambiente...${RESET}"

if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${BLUE}- Arquivo .env criado a partir de .env.example.${RESET}"
    elif [ -f "run/.env.example" ]; then
        cp run/.env.example .env
        echo -e "${BLUE}- Arquivo .env criado a partir de run/.env.example.${RESET}"
    else
        echo -e "${RED}ERRO: Nenhum arquivo .env.example encontrado.${RESET}"
        exit 1
    fi
fi

if [ -f "run/.env" ]; then
    echo -e "${BLUE}- Atualizando e descomentando credenciais...${RESET}"
    
    grep -v '^#' run/.env | grep -v '^$' | while read -r line ; do
        key=$(echo "$line" | cut -d '=' -f 1)
        value=$(echo "$line" | cut -d '=' -f 2-)
        
        if grep -qE "^\s*#?\s*$key=" .env; then
            
            if [[ "$OSTYPE" == "darwin"* ]]; then
                sed -i '' "s|^[#[:space:]]*$key=.*|$key=$value|" .env
            else
                sed -i "s|^[#[:space:]]*$key=.*|$key=$value|" .env
            fi
        else
            echo "$key=$value" >> .env
        fi
    done
    echo -e "${GREEN}Arquivo .env sincronizado!${RESET}\n"
else
    echo -e "${RED}AVISO: run/.env não encontrado. Usando .env existente.${RESET}\n"
fi

# ==============================================================================
# 4. Infraestrutura Docker
# ==============================================================================
echo -e "${YELLOW}2. Subindo containers...${RESET}"

if [ -f "run/docker-compose.yml" ]; then
    $DOCKER_CMD -f run/docker-compose.yml up -d --build
else
    echo -e "${RED}ERRO: run/docker-compose.yml não encontrado.${RESET}"
    exit 1
fi

# ==============================================================================
# 5. Healthcheck do Banco de Dados
# ==============================================================================
echo -ne "${BLUE}Aguardando Postgres ficar pronto...${RESET}"

if [ -f .env ]; then export $(grep -v '^#' .env | xargs); fi

DB_USER=${DB_USERNAME:-cawe}
DB_NAME=${DB_DATABASE:-laravel}
CONTAINER_DB_NAME="cawe_blog_db" 

until docker exec $CONTAINER_DB_NAME pg_isready -U "$DB_USER" -d "$DB_NAME" > /dev/null 2>&1; do
  echo -n "."
  sleep 1
done
echo -e " ${GREEN}Pronto!${RESET}\n"

# ==============================================================================
# 6. Configuração do Laravel
# ==============================================================================
if [ -f "artisan" ]; then
    echo -e "${YELLOW}3. Rodando migrações...${RESET}"
    php artisan migrate --force
    php artisan db:seed --force
    php artisan filament:assets
    php artisan view:clear
    php artisan serve
else
    echo -e "${RED}AVISO: 'artisan' não encontrado. Migrações ignoradas.${RESET}"
fi

echo -e "\n${GREEN}### AMBIENTE ONLINE ###${RESET}"
echo -e "${BLUE}Laravel: http://localhost:8000${RESET}\n"