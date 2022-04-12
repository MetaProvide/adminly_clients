# This file is licensed under the Affero General Public License version 3 or
# later. See the LICENSE file.

app_name=adminly_clients
build_directory=$(CURDIR)/build
temp_build_directory=$(build_directory)/temp
build_tools_directory=$(CURDIR)/build/tools

all: dev-setup lint build-js-production test

release: npm-init build-js-production build-tarball
# Dev env management
dev-setup: clean clean-dev composer npm-init

lint: eslint stylelint prettier php-cs

lint-fix: eslint-fix stylelint-fix prettier-fix php-cs-fix

# Dependencies
composer:
	composer install --prefer-dist

composer-update:
	composer update --prefer-dist

npm-init:
	npm ci

npm-update:
	npm update

# Building
build-js:
	npm run dev

build-js-production:
	npm run build

watch-js:
	npm run watch

serve-js:
	npm run serve

# Linting
eslint:
	npm run eslint

eslint-fix:
	npm run eslint:fix

# Style linting
stylelint:
	npm run stylelint

stylelint-fix:
	npm run stylelint:fix

# Prettier
prettier:
	npm run prettier

prettier-fix:
	npm run prettier:fix

# PHP CS Fixer
php-cs:
	vendor/bin/php-cs-fixer fix -v --dry-run

php-cs-fix:
	vendor/bin/php-cs-fixer fix -v

# Cleaning
clean:
	rm -rf js/*

clean-dev:
	rm -rf node_modules

# Tests
test:
	npm run test
	./vendor/phpunit/phpunit/phpunit -c phpunit.xml

build-tarball:
	rm -rf $(build_directory)
	mkdir -p $(temp_build_directory)
	rsync -a \
	--exclude=".git" \
	--exclude=".github" \
	--exclude=".vscode" \
	--exclude="node_modules" \
	--exclude="build" \
	--exclude="vendor" \
	--exclude=".editorconfig" \
	--exclude=".gitignore" \
	--exclude=".php_cs.dist" \
	--exclude=".prettierrc" \
	--exclude=".stylelintrc.json" \
	--exclude="composer.json" \
	--exclude="composer.lock" \
	--exclude="Makefile" \
	--exclude="package-lock.json" \
	--exclude="package.json" \
	../$(app_name)/ $(temp_build_directory)/$(app_name)
	tar czf $(build_directory)/$(app_name).tar.gz \
		-C $(temp_build_directory) $(app_name)

