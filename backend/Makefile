setup-sail:
	./vendor/bin/sail up -d
	@echo "Waiting for the database to be ready..."
	@until ./vendor/bin/sail exec pgsql pg_isready -U sail -d systock_challenge; do \
		sleep 2; \
	done
	./vendor/bin/sail artisan migrate --seed

test-sail:
	./vendor/bin/sail up -d
	./vendor/bin/sail artisan test

down-sail:
	./vendor/bin/sail down --rmi local -v

up-local:
	docker compose -f compose.dev.yaml up -d

test-local:
	docker compose -f compose.dev.yaml exec workspace php artisan test

down-local:
	docker compose -f compose.dev.yaml down --rmi local -v
