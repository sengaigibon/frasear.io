# Podman commands for the frasear stack.
#
# COMPOSE_CMD picks which compose implementation to use. Fedora setups vary:
# some have the standalone python `podman-compose`, others have the newer
# built-in `podman compose` (compose plugin). Check with:
#   which podman-compose   ||   podman compose version
# and override at call time if the default here doesn't match what you have:
#   make up COMPOSE_CMD="podman compose"
COMPOSE_CMD ?= podman-compose

PROD_FILE := compose.yml
DEV_FILE  := compose.dev.yml

PROD := $(COMPOSE_CMD) -f $(PROD_FILE)
DEV  := $(COMPOSE_CMD) -f $(DEV_FILE)

.DEFAULT_GOAL := help

## --- Production-like stack (app + nginx + postgres + cloudflared) ---

.PHONY: build
build: ## Build the app image
	$(PROD) build

.PHONY: up
up: ## Start the full stack in the background
	$(PROD) up -d

.PHONY: down
down: ## Stop the full stack (keeps volumes/data)
	$(PROD) down

.PHONY: restart
restart: down up ## Restart the full stack

.PHONY: ps
ps: ## Show running containers for this stack
	$(PROD) ps

.PHONY: logs
logs: ## Follow logs for all services
	$(PROD) logs -f

.PHONY: logs-app
logs-app: ## Follow logs for the app container only
	$(PROD) logs -f app

## --- Temporary: testing via Cloudflare Quick Tunnel (no domain needed yet) ---

QUICK := $(COMPOSE_CMD) -f $(PROD_FILE) -f compose.quick-tunnel.yml

.PHONY: quick-tunnel-up
quick-tunnel-up: ## Start the stack with a throwaway *.trycloudflare.com URL — no domain needed
	$(QUICK) up -d

.PHONY: quick-tunnel-url
quick-tunnel-url: ## Print the random public URL from the cloudflared logs
	$(QUICK) logs cloudflared | grep -o 'https://[a-z0-9-]*\.trycloudflare\.com' | tail -1

.PHONY: quick-tunnel-down
quick-tunnel-down: ## Stop the quick-tunnel stack
	$(QUICK) down

## --- Dev stack (Postgres only — run artisan/npm on the host) ---

.PHONY: dev-up
dev-up: ## Start local dev Postgres only
	$(DEV) up -d

.PHONY: dev-down
dev-down: ## Stop local dev Postgres
	$(DEV) down

.PHONY: dev-logs
dev-logs: ## Follow dev Postgres logs
	$(DEV) logs -f

## --- Laravel commands inside the running app container ---

.PHONY: shell
shell: ## Open a shell inside the app container
	$(PROD) exec app bash

.PHONY: tinker
tinker: ## Open artisan tinker inside the app container
	$(PROD) exec app php artisan tinker

.PHONY: artisan
artisan: ## Run any artisan command: make artisan cmd="route:list"
	$(PROD) exec app php artisan $(cmd)

.PHONY: migrate
migrate: ## Run pending migrations
	$(PROD) exec app php artisan migrate --force

.PHONY: migrate-fresh
migrate-fresh: ## Drop all tables and re-run migrations + seeders. DESTRUCTIVE.
	@echo "This drops every table in the container's database."
	@read -p "Type 'yes' to continue: " ans && [ "$$ans" = "yes" ]
	$(PROD) exec app php artisan migrate:fresh --seed --force

.PHONY: test
test: ## Run the test suite inside the app container
	$(PROD) exec app php artisan test

.PHONY: key-generate
key-generate: ## Generate APP_KEY (first boot only)
	$(PROD) exec app php artisan key:generate

.PHONY: cache-clear
cache-clear: ## Clear config/route/view caches (useful after debugging a runtime cache issue)
	$(PROD) exec app php artisan config:clear
	$(PROD) exec app php artisan route:clear
	$(PROD) exec app php artisan view:clear

## --- Database backup/restore (delegates to scripts/) ---

.PHONY: backup
backup: ## Run a Postgres backup now
	./scripts/backup.sh

.PHONY: restore
restore: ## Restore a backup: make restore file=/path/to/dump
	./scripts/restore.sh $(file)

## --- Cleanup ---

.PHONY: clean
clean: ## Remove stopped containers and dangling images (keeps volumes/data)
	podman container prune -f
	podman image prune -f

.PHONY: nuke
nuke: ## Stop stack AND delete volumes — destroys the database. DESTRUCTIVE.
	@echo "This deletes pgdata, app_public and storage volumes permanently."
	@read -p "Type 'yes' to continue: " ans && [ "$$ans" = "yes" ]
	$(PROD) down -v

## --- Host development (NOT Podman — runs directly on your machine) ---
## Requires: postgres running via `make dev-up`, and `npm install` already done.

.PHONY: serve
serve: ## Run php artisan serve + npm run dev together (Ctrl+C stops both)
	npx concurrently -k -n "ARTISAN,VITE" -c "green,cyan" \
		"php artisan serve" \
		"npm run dev"

## --- Help ---

.PHONY: help
help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-16s\033[0m %s\n", $$1, $$2}'
