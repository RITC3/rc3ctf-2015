PROGNAME := aandxor64
CC := aarch64-linux-gnu-gcc-4.9

all: debug

final: clean
	$(CC) -o $(PROGNAME) -std=c99 -static $(PROGNAME).c

finalosx: clean
	$(CC) -o $(PROGNAME) -std=c99 $(PROGNAME).c

debug: clean
	$(CC) -o $(PROGNAME) -ggdb -std=c99 $(PROGNAME).c

clean:
	rm -fr $(PROGNAME)
