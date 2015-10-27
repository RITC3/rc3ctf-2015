all: debug

final:
	gcc -o FinalJeopardy -std=c99 FinalJeopardy.c

debug: 
	gcc -o FinalJeopardy -std=c99 -ggdb FinalJeopardy.c

clean:
	rm -fr FinalJeopardy
