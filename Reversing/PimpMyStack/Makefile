PROGNAME := PimpMyStack

all: debug

final: clean
	gcc -o $(PROGNAME) -std=c99 -m32 -fno-stack-protector -z execstack -static $(PROGNAME).c

debug: clean
	gcc -o $(PROGNAME) -ggdb -m32 -fno-stack-protector -z execstack -DDEBUG -std=c99 $(PROGNAME).c

clean:
	rm -fr $(PROGNAME)
