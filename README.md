# Systock Test

Guia de execução do projeto (backend Laravel + frontend Vue) em ambiente local.

## Pré-requisitos

- Docker
- Docker Compose v2
- Make (opcional, mas recomendado para usar atalhos)

## Configuração inicial

1. Na raiz do projeto, copie o arquivo de ambiente da infraestrutura:

```bash
cp .env.example .env
```

2. No backend, copie o arquivo de ambiente da aplicação:

```bash
cp backend/.env.example backend/.env
```

3. (Opcional) Ajuste variáveis de banco no `.env` da raiz, se necessário:

```env
DB_DATABASE=systock_challenge
DB_USERNAME=postgres
DB_PASSWORD=password
```

## Subir o projeto

Na raiz do projeto:

```bash
make up
```

Sem Make:

```bash
docker compose -f compose.yaml up -d
```

## Preparar o backend (primeira execução)

Após subir os containers, rode:

```bash
docker compose -f compose.yaml exec workspace composer install
docker compose -f compose.yaml exec workspace php artisan key:generate
docker compose -f compose.yaml exec workspace php artisan migrate
```

## Acessos

- Frontend: http://localhost:5173
- Backend (API): http://localhost

Exemplo de endpoint:

- http://localhost/api/v1/users

## Comandos úteis

Subir ambiente:

```bash
make up
```

Parar e remover containers/volumes locais:

```bash
make down
```

Executar testes do backend:

```bash
docker compose -f compose.yaml exec workspace php artisan test
```

## Observações

- O container do frontend instala dependências automaticamente e sobe o Vite na porta `5173`.
- Se alterar variáveis no `.env`, reinicie os serviços para garantir que tudo seja recarregado.
