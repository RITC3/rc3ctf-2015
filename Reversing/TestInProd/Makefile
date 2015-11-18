PROGNAME := TestInProd

all: debug

final: clean
	gcc -o $(PROGNAME) -std=c99 -ggdb $(PROGNAME).c

debug: clean
	gcc -o $(PROGNAME) -ggdb -std=c99 $(PROGNAME).c

clean:
	rm -fr $(PROGNAME)
