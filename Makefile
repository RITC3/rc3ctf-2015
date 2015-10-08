all: reversing102

reversing102: clean
	nasm -f macho64 reversing102.S
	ld -lSystem -macosx_version_min 10.7.0 -o reversing102 reversing102.o -lc

clean:
	rm -rf *.o reversing102
