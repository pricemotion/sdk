test : vendor/autoload.php
	@vendor/bin/phpunit test
.PHONY : test

watch :
	@git ls-files '*.php' | entr -r -c $(MAKE) -s test
.PHONY : watch

vendor/autoload.php :
	@composer install
