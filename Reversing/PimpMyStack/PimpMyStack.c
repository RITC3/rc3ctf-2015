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

int main(int argc, char *argv[])
{
    short size = 0;
    puts("Please Pimp My Stack!");
    printf("Enter size of input: ");
    scanf("%hd", &size);
    char buf[size+1];
    memset(buf, 0, size+1);
    for (short i=0; i!=size; i++){
        read(0, buf+i, 1);
        if (buf[i] == 0)
            break;
#ifdef DEBUG
        printf("i: %d, size: %hd, buf@i: %d\n", i, size, buf[i]);
#endif
    }
    printf("%s\n", buf);
    return 0;
}
