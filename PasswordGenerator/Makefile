PROGNAME := PasswordGenerator

all: debug

final: clean
	gcc -o $(PROGNAME) -std=c99 -m32 -static $(PROGNAME).c

debug: clean
	gcc -o $(PROGNAME) -ggdb -m32 -DDEBUG -std=c99 $(PROGNAME).c

clean:
	rm -fr $(PROGNAME)
