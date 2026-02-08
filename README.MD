# Blog - Laravel, Filament & Inertia

Um sistema de blog moderno e de alta performance, construído com o que há de mais recente no ecossistema Laravel. Este projeto foca em uma experiência de desenvolvimento excepcional e uma interface de usuário fluida.

## 🛠️ Tech Stack & Versões

Este projeto utiliza as versões mais recentes (bleeding edge) das ferramentas:

* #### Backend Framework: Laravel 12.0

* #### Painel Administrativo: Filament 5.0

* #### Frontend Bridge: Inertia.js 2.0

* #### Frontend Framework: Vue.js 3 (Composition API)

* #### Linguagem: PHP 8.2+

* #### Testes: Pest PHP 4.3

* #### Syntax Highlighting: Phiki 2.1

* #### Infraestrutura: Docker & Docker Compose

## ✨ Funcionalidades

* #### Gestão completa de Posts, Categorias, Tags e Referências.

* #### Plugin Tiptap customizado para referências bibliográficas.

* #### Geração automática de slugs e pré-visualização.

## Frontend SPA (Inertia v2):

* #### Navegação instantânea sem recarregamento de página.

* #### Server-Side Rendering (SSR) configurado.

* #### Busca global integrada.

* #### Professional/Personal mode.

## 🚀 Instalação e Configuração

### Pré-requisitos

* Docker e Docker Compose instalados.

* Node.js (v20+) e NPM.

### Passo a Passo

1. Clone o repositório:

2. git clone [https://github.com/seu-usuario/blog.git](https://github.com/seu-usuario/blog.git)
3. cd blog


### Configure as variáveis de ambiente:

> * cp .env.example .env


### Inicie o ambiente Docker:
#### Utilize o script facilitador para subir os containers (App, DB, Redis, Nginx):

> * ./setup_dev.sh


#### Ou manualmente via Docker Compose:

> * docker-compose -f run/docker-compose.yml up -d


## Instale as dependências:

###  Dependências PHP (dentro do container ou local se tiver PHP 8.2+)
> * docker-compose -f run/docker-compose.yml exec php composer install

### Dependências Frontend
> * npm install


## Setup do Banco de Dados:

> docker-compose -f run/docker-compose.yml exec php php artisan migrate --seed


## Inicie o servidor de desenvolvimento (Vite):

> npm run dev


* O projeto estará acessível em http://localhost:8000 (ou a porta definida no seu .env).

# 🤝 Contribuição

### Contribuições são o que fazem a comunidade open source um lugar incrível para aprender e criar.

1. Faça um Fork do projeto.

2. Crie a sua Feature Branch (git checkout -b feature/AmazingFeature).

3. Faça o Commit das alterações (git commit -m 'Add: New Amazing Feature').

4. Push para a Branch (git push origin feature/AmazingFeature).

5. Abra um Pull Request.