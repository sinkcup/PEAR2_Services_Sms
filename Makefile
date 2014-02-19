.PHONY : package
package:
	@pear channel-discover sinkcup.github.io/pear; onion build --pear;
install:
	@pear install PEAR2_Services_Sms-*.tgz;
uninstall:
	@pear uninstall sinkcup/PEAR2_Services_Sms;
test:
	@phpunit ./tests/
.PHONY : docs
docs:
	@rm -rf ./docs/*.html; phpdoc -d ./src/ -t ./docs --template abstract;
