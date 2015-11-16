/*
 * =====================================================================================
 *
 *       Filename:  JumpFu.c
 *
 *    Description:  Jump around boiiiiii
 *
 *        Version:  1.0
 *        Created:  11/13/2015 21:29:06
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  Jaime Geiger (@jgeigerm), jmg2967@rit.edu
 *
 * =====================================================================================
 */
#include <stdio.h>
#include <stdlib.h>
#include <fcntl.h>
#include <unistd.h>
#include <stdbool.h>
#include <string.h>
#include <time.h>

#ifdef DEBUG
#define DBUG(a) printf("\t%s\n", a); \
    printf("Zero: %lu, giveme: %d\n", zero, giveme);
#else
#define DBUG(a) if (1){}
#endif

void print_flag(){
    char flagbuf[14] = {0};
    int fd = open("flag.txt", O_RDONLY);
    if (fd < 0) {
        puts("Error opening flag file :(");
        return;
    }
    read(fd, flagbuf, 13);
    printf("%s\n", flagbuf);
}

int main(int argc, char *argv[])
{
    srand(time(NULL));
    bool giveme = false;
    long zero = 0;
    char buf[100];
    bool gotflag = false;
    puts("Welcome to hell, we have real strong JumpFu here");
start:
    DBUG("start")
    puts("Where to?");
    fflush(0);
    zero = 0;
    fgets(buf, 117, stdin);
    buf[99] = 0;
    if (strncmp(buf+10, "WHO GOES THERE", strlen("WHO GOES THERE")) == 10)
        goto six;
    else
        goto two;
one:
    DBUG("one")
    puts("WELCOME BACK");
    goto start;
two:
    DBUG("two")
    puts("Who are you? Where are you even going?");
    fflush(0);
    if (buf[69]=='8')
        goto eleven;
    if (buf[69]=='9')
        goto five;
    goto one;
three:
    DBUG("three")
    puts("Here we go again...");
    fflush(0);
    goto eight;
four:
    DBUG("four")
    if (!zero)
        goto nine;
five:
    DBUG("five")
    giveme = true;
    if (!zero)
        goto eight;
six:
    DBUG("six")
    giveme = false;
seven: //lucky number seven
    DBUG("seven")
    if (giveme){
        gotflag = true;
        print_flag();
        goto end;
    }else{
        puts("NO!!! GO AWAY");
        fflush(0);
    }
eight:
    DBUG("eight")
    puts("Goodnight! See you later!");
    fflush(0);
    sleep(1);
    if (rand() % 2)
        goto one;
    else
        goto twelve;
nine:
    DBUG("nine")
    goto two;
ten:
    DBUG("ten")
    if (!zero)
        goto end;
    goto one;
eleven:
    DBUG("eleven")
    if (strncmp(&zero, "risky!", strlen("risky")))
        goto eight;
    goto six;
twelve:
    DBUG("twelve")
    if (zero)
        goto seven;
    else
        goto ten;
end:
    DBUG("end")
    if (!gotflag)
        puts("Looks like you are leavin here empty handed.");
    else
        puts("Nice work. You won.");
    fflush(0);
    return 0;
}
