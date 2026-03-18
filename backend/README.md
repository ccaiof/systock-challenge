# Systock Test Backend

API REST em Laravel 12 para cadastro e gestão de usuários, com validações de negócio, tratamento padronizado de erros e suíte de testes automatizados.

## Objetivo do projeto

Este projeto expõe endpoints de CRUD de usuários e produtos com autenticação.

Principais funcionalidades:

- Login de usuário.
- Registro de usuário.
- Listagem paginada de usuários.
- Consulta de usuário por ID.
- Atualização de usuário.
- Remoção de usuário.
- Validação de CPF e validações de formulário.

## Stack utilizada

- PHP 8.4+
- Laravel 12
- PostgreSQL 18
- Docker e Docker Compose
- PHPUnit 11

## Pré-requisitos

Opção recomendada (Docker):

- Docker
- Docker Compose v2
- Make (opcional, para atalhos)

Opção local (sem Docker):

- PHP 8.2+
- Composer
- PostgreSQL

## Como executar com Docker (recomendado)

1. Copie o arquivo de ambiente:

~~~bash
cp .env.example .env
~~~

2. Ajuste variáveis de banco no .env (mínimo):

~~~env
DB_CONNECTION=pgsql
DB_HOST=database
DB_PORT=5432
DB_DATABASE=backend
DB_USERNAME=postgres
DB_PASSWORD=postgres
~~~

3. Suba os containers:

~~~bash
make up
~~~

Se não tiver Make:

~~~bash
docker compose -f compose.dev.yaml up -d
~~~

4. Instale dependências PHP:

~~~bash
docker compose -f compose.dev.yaml exec workspace composer install
~~~

5. Gere a chave da aplicação:

~~~bash
docker compose -f compose.dev.yaml exec workspace php artisan key:generate
~~~

6. Rode as migrations:

~~~bash
docker compose -f compose.dev.yaml exec workspace php artisan migrate
~~~

7. Acesse a API:

- http://localhost/api/v1/users

## Como executar sem Docker

1. Copie o ambiente:

~~~bash
cp .env.example .env
~~~

2. Configure o banco no .env para sua instância local do PostgreSQL.

3. Instale dependências:

~~~bash
composer install
~~~

4. Gere a chave e rode migrations:

~~~bash
php artisan key:generate
php artisan migrate
~~~

5. Suba o servidor:

~~~bash
php artisan serve
~~~

6. Acesse:

- http://127.0.0.1:8000/api/v1/users

## Como executar os testes

Com Docker (deve executar o comando `make up` antes):

~~~bash
make test
~~~

Sem Make:

~~~bash
docker compose -f compose.dev.yaml exec workspace php artisan test
~~~

Sem Docker:

~~~bash
php artisan test
~~~

Executar apenas uma classe de teste:

~~~bash
php artisan test --filter=UpdateUserTest
~~~

## Cobertura de testes

O projeto já possui configuração de coverage no phpunit.xml para gerar relatórios em:

- tmp/coverage/index.html
- tmp/coverage.txt
- tmp/logs/clover.xml

## Comandos úteis

Subir ambiente de desenvolvimento:

~~~bash
make up
~~~

Executar testes:

~~~bash
make test
~~~

Parar e limpar ambiente Docker de desenvolvimento:

~~~bash
make down
~~~

## Troubleshooting rápido

- Erro de conexão com banco:
	confira DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME e DB_PASSWORD no .env.

- Erro de chave de aplicação:
	rode php artisan key:generate.

- Falha de migrations:
	confirme se o container database está saudável antes de executar migrate.

- Testes sem cobertura:
	habilite XDEBUG_MODE=coverage na execução.
