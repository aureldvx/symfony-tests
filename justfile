EXEC := "docker compose exec php"
PHP := EXEC + " php"
COMPOSER := EXEC + " composer"
SYMFONY := EXEC + " symfony"
NPM := EXEC + " npm"

# *******************************
# Global aliases
# *******************************

# composer alias
composer +arguments:
	COMPOSER_ALLOW_SUPERUSER=1 {{COMPOSER}} {{arguments}}

# symfony console alias
console *arguments:
	{{SYMFONY}} console {{arguments}}

# npm alias
npm +arguments:
	{{NPM}} {{arguments}}

# *******************************
# Application
# *******************************

# Clear caches
cc env='dev':
	{{SYMFONY}} console cache:clear --env={{env}}

controller:
    {{SYMFONY}} console make:controller

entity:
    {{SYMFONY}} console make:entity

form:
	{{SYMFONY}} console make:form

voter:
	{{SYMFONY}} console make:voter

pretest:
    {{SYMFONY}} console --env=test doctrine:database:drop --force
    {{SYMFONY}} console --env=test doctrine:database:create
    {{SYMFONY}} console --env=test doctrine:schema:update --complete --force
    {{SYMFONY}} console --env=test doctrine:fixtures:load --no-interaction

test *path:
    {{PHP}} bin/phpunit {{path}}

watch:
    {{NPM}} run watch

# *******************************
# Database
# *******************************

create:
    {{SYMFONY}} console doctrine:database:create

migration:
    {{SYMFONY}} console make:migration

migrate:
    {{SYMFONY}} console doctrine:migrations:migrate

diff:
    {{SYMFONY}} console doctrine:migrations:diff

drop:
    {{SYMFONY}} console doctrine:database:drop --force

recreate: drop create migrate

# Load fixtures (add `--group=<name>` to launch specific fixtures)
seed *arguments:
	{{SYMFONY}} console doctrine:fixtures:load {{arguments}} --no-interaction

# *******************************
# Environment related
# *******************************

# Deploy to production server.
# Append the ssh destination at the end, eg. my_ssh_server:/my/directory
deploy destination:
	rsync -avz --exclude-from=".rsyncignore.txt" --delete ./ {{destination}}
