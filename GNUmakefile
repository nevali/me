PHP ?= php
OUTPUT = public_html
OUTPUT_REPO = git@github.com:nevali/me.git
OUTPUT_BRANCH = gh-pages
DATA = _data
SOURCE = _source
STATIC = _static
CONFIG = _config
TEMPLATES = _templates

CONFIGFILES = $(CONFIG)/defaults.php $(CONFIG)/config.php
TEMPLATEFILES = $(wildcard $(TEMPLATES)/*.phtml)

OUTPUTFILES := $(OUTPUT)/index.html \
	$(OUTPUT)/whereami/index.html
OUTPUTDIRS := $(dir $(OUTPUTFILES))

LOGPREFIX = $(MAKE)[$(MAKELEVEL)]

all: preflight $(OUTPUTFILES)

autobuild:
	@git clone -b $(OUTPUT_BRANCH) $(OUTPUT_REPO) $(OUTPUT)
	@$(MAKE) clean
	@$(MAKE) all
	@cd $(OUTPUT) && \
		git add -A && \
		if test `git status --porcelain | wc -l` -gt 0 ; then \
			git commit -a -m"Autobuild commit." || exit $? ; \
			git push $(OUTPUT_REPO) gh-pages || exit $? ; \
		else \
			echo "$(LOGPREFIX): No changes to push" >&2 ; \
		fi

clean:
	@echo "$(LOGPREFIX): Cleaning $(OUTPUT)..." >&2
	@rm -f $(OUTPUTFILES)
	@cd $(OUTPUT) && for i in .ignore * ; do \
		case $$i in \
			.*) ;; \
			 *) test -d $$i || rm -f $$i ;; \
		esac ; \
	done

preflight:
	@echo "$(LOGPREFIX): Creating directories" >&2
	mkdir -p $(OUTPUTDIRS)
	@$(MAKE) static
	@$(MAKE) images

rebuild:
	$(MAKE) clean
	$(MAKE) all

images: $(OUTPUT)
	@echo "$(LOGPREFIX): Copying profile images" >&2
	( cd $(DATA) && find . -name \*.jpeg | xargs tar cf - ) | ( cd $(OUTPUT) && tar xf - )

static: $(OUTPUT)
	@echo "$(LOGPREFIX): Copying static resources" >&2
	( cd $(STATIC) && tar cf - .) | ( cd $(OUTPUT) && tar xf - )

$(OUTPUT)/index.html: $(SOURCE)/index.php $(TEMPLATEFILES) $(CONFIGFILES)
	$(PHP) -f $< > $@

$(OUTPUT)/whereami/index.html: $(SOURCE)/whereami.php $(TEMPLATEFILES) $(CONFIGFILES)
	$(PHP) -f $< > $@
