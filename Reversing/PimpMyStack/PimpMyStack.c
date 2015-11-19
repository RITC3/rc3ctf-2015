/*
 * =====================================================================================
 *
 *       Filename:  PimpMyStack.c
 *
 *    Description:  Basic stack based buffer overflow pwn challenge
 *
 *        Version:  1.0
 *        Created:  11/16/2015 20:45:20
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
#include <string.h>
#include <unistd.h>
#include <time.h>

short size = 0;
int canary2;

int main(int argc, char *argv[])
{
    srand(time(NULL));
    int canary = canary2 = rand();
    puts("Please Pimp My Stack!");
    printf("Enter size of input: ");
    fflush(0);
    scanf("%hd", &size);
    char buf[size+1];
    memset(buf, 0, size+1);
    printf("Buffer @0x%X, put sum datas in dere: ", buf);
    fflush(0);
    for (short i=0; i!=size; i++){
        buf[i] = fgetc(stdin);
        if (feof(stdin))
            break;
    }
    if (canary != canary2){
        puts("EY NONE OF THAT!");
        exit(-69);
    }
    return 0;
}
