TARGETS=favicon32.ico favicon32.png favicon64.png favicon128.png

default: $(TARGETS)

favicon32.ico: favicon32.png
	convert favicon32.png favicon32.ico

favicon32.png: favicon.svg
	inkscape -z -e favicon32.png -w 32 -h 32 favicon.svg

favicon64.png: favicon.svg
	inkscape -z -e favicon64.png -w 64 -h 64 favicon.svg

favicon128.png: favicon.svg
	inkscape -z -e favicon128.png -w 128 -h 128 favicon.svg

clean:
	rm -f $(TARGETS)

