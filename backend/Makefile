up:
	docker compose -f compose.dev.yaml up -d

test:
	docker compose -f compose.dev.yaml exec workspace php artisan test

down:
	docker compose -f compose.dev.yaml down --rmi local -v
