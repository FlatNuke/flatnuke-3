# Flatnuke version
VER = "3.1.6"
DEV ?=
# Current user name
USER = $(shell whoami)
# Current date
DATE = $(shell date +%Y%m%d)
# Filename for public release
FILEDIST = flatnuke$(DEV)-$(VER).tar.gz
# Filename for snapshot release
FILE = flatnuke$(DEV)-$(VER)-$(DATE).tar.gz
# Directory where to create the package
FNUSER = $(shell cat ~/.flatnukerc)
# Directory of the webserver
WEBDIR=/var/www

snapshot:
	@cd ..;\
	rm -fr $(FILE) $(FILE).md5;\
	cp -dpR flatnuke$(DEV) flatnuke$(DEV)-$(VER);\
	find flatnuke$(DEV)-$(VER) -name CVS -exec rm -fr \{\} \; 2>/dev/null;\
	find flatnuke$(DEV)-$(VER) -name "\.*" -exec rm -f \{\} \; 2>/dev/null;\
	rm flatnuke$(DEV)-$(VER)/Makefile;\
	tar vfzc $(FILE) flatnuke$(DEV)-$(VER) > /dev/null;\
	rm -fr flatnuke$(DEV)-$(VER);\
	md5sum $(FILE) | cut -d" " -f1 > $(FILE).md5;\
	scp $(FILE) $(FILE).md5 $(FNUSER);\
	rm -fr $(FILE) $(FILE).md5;

dist:
	@cd ..;\
	rm -fr $(FILEDIST);\
	cp -dpR flatnuke$(DEV) flatnuke$(DEV)-$(VER);\
	find flatnuke$(DEV)-$(VER) -name CVS -exec rm -fr \{\} \; 2>/dev/null;\
	find flatnuke$(DEV)-$(VER) -name "\.*" -exec rm -fr \{\} \; 2>/dev/null;\
	rm flatnuke$(DEV)-$(VER)/Makefile;\
	tar vfzc $(FILEDIST) flatnuke$(DEV)-$(VER) > /dev/null;\
	rm -fr flatnuke$(DEV)-$(VER);\
	scp $(FILEDIST) $(FNUSER);\
	rm -fr $(FILEDIST)

webtest:
	@cd ..;\
	rm -fr $(WEBDIR)/flatnuke$(DEV)-$(VER);\
	cp -dpR flatnuke$(DEV) flatnuke$(DEV)-$(VER);\
	find flatnuke$(DEV)-$(VER) -name CVS -exec rm -fr \{\} \; 2>/dev/null;\
	find flatnuke$(DEV)-$(VER) -name "\.*" -exec rm -fr \{\} \; 2>/dev/null;\
	rm flatnuke$(DEV)-$(VER)/Makefile;\
	mv flatnuke$(DEV)-$(VER) $(WEBDIR);\
	chown -R $(USER):$(USER) $(WEBDIR)/flatnuke$(DEV)-$(VER);

cleantest:
	@su -c "rm -fr $(WEBDIR)/flatnuke$(DEV)-$(VER)"
