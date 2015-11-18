PROGNAME := PimpMyStack

all: debug

final: clean
	gcc -o $(PROGNAME) -std=c99 -fno-stack-protector -static $(PROGNAME).c

debug: clean
	gcc -o $(PROGNAME) -ggdb -fno-stack-protector -DDEBUG -std=c99 $(PROGNAME).c

clean:
	rm -fr $(PROGNAME)
