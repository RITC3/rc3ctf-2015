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

void print_flag(){
    char flagbuf[14] = {0};
    int fd = open("/challenges/pw/flag.txt", O_RDONLY);
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
    char *buf = malloc(100);
    bool gotflag = false;
    puts("Welcome to hell, we have real strong JumpFu here");
start:
#ifdef DEBUG
    puts("start");
#endif
    puts("Where to?");
    fflush(0);
    fgets(buf, 105, stdin);
    buf[99] = 0;
    if (strncmp(buf+10, "WHO GOES THERE", strlen("WHO GOES THERE")) == 10)
        goto six;
    else
        goto two;
one:
#ifdef DEBUG
    puts("one");
#endif
    puts("WELCOME BACK");
    goto start;
two:
#ifdef DEBUG
    puts("two");
#endif
    puts("Who are you? Where are you even going?");
    fflush(0);
    if (buf[69]=='8')
        goto eight;
    if (buf[69]=='9')
        goto five;
    goto one;
three:
#ifdef DEBUG
    puts("three");
#endif
    puts("Here we go again...");
    fflush(0);
    goto eight;
four:
#ifdef DEBUG
    puts("four");
#endif
    if (!zero)
        goto nine;
five:
#ifdef DEBUG
    puts("five");
#endif
    giveme = true;
six:
#ifdef DEBUG
    puts("six");
#endif
    giveme = false;
seven: //lucky number seven
#ifdef DEBUG
    puts("seven");
#endif
    if (giveme){
        gotflag = true;
        print_flag();
    }else{
        puts("NO!!! GO AWAY");
        fflush(0);
    }
eight:
#ifdef DEBUG
    puts("eight");
#endif
    puts("Goodnight! See you later!");
    fflush(0);
    sleep(1);
    if (rand() % 3)
        goto three;
    else
        goto four;
nine:
#ifdef DEBUG
    puts("nine");
#endif
    goto two;
ten:
#ifdef DEBUG
    puts("ten");
#endif
    if (!zero)
        giveme = true;
    goto one;
eleven:
#ifdef DEBUG
    puts("eleven");
#endif
    goto six;
twelve:
#ifdef DEBUG
    puts("twelve");
#endif
    if (zero)
        goto seven;
    else
        goto ten;
end:
#ifdef DEBUG
    puts("end");
#endif
    if (!gotflag)
        puts("Looks like you are leavin here empty handed.");
    else
        puts("Nice work. You won.");
    fflush(0);
    return 0;
}
