all: debug

final: clean
	gcc -o FinalJeopardy -std=c99 FinalJeopardy.c

debug: clean
	gcc -o FinalJeopardy -std=c99 -ggdb FinalJeopardy.c

clean:
	rm -fr FinalJeopardy
