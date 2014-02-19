package:
	@pear channel-discover sinkcup.github.io/pear; onion build --pear;
install:
	@pear install PEAR2_Services_Sms-*.tgz;
uninstall:
	@pear uninstall sinkcup/PEAR2_Services_Sms;
test:
	@phpunit ./tests/
docs:
	@rm -rf ./docs/*; phpdoc -d ./src/ -t ./docs --template abstract;
