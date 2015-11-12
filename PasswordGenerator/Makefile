PROGNAME := PasswordGenerator

all: debug

final: clean
	gcc -o $(PROGNAME) -std=c99 $(PROGNAME).c

debug: clean
	gcc -o $(PROGNAME) -ggdb -DDEBUG -std=c99 $(PROGNAME).c

clean:
	rm -fr $(PROGNAME)
