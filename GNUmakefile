test : vendor/autoload.php
	@vendor/bin/phpunit test
.PHONY : test

watch :
	@git ls-files '*.php' | entr -r -c $(MAKE) -s test
.PHONY : watch

doc : vendor/bin/phpdoc
	@rm -rf doc
	@vendor/bin/phpdoc run
.PHONY : doc

vendor/autoload.php :
	@composer install

vendor/bin/phpdoc :
	wget -O $@~ https://github.com/phpDocumentor/phpDocumentor/releases/download/v3.3.0/phpDocumentor.phar
	chmod +x $@~
	mv $@~ $@
