all: BoD

BoD: clean
	nasm -f macho64 BoD.asm
	ld -lSystem -macosx_version_min 10.7.0 -o BoD BoD.o -lc
	strip BoD

clean:
	rm -rf *.o BoD
