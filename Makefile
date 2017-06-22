project_dir = $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

.PHONY: code-style
code-style: php-cs-fixer

.PHONY: php-cs-fixer
php-cs-fixer:
	docker run --rm -v $(project_dir):/project herloct/php-cs-fixer fix

.PHONY: test
test:
	"./vendor/bin/phpunit"
#	docker run --rm -v $(project_dir):/usr/src/test -w /usr/src/test php:7.1-alpine /bin/sh -c "docker-php-ext-install bcmath; vendor/bin/phpunit"
