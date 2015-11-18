/*
 * =====================================================================================
 *
 *       Filename:  PasswordGenerator.c
 *
 *    Description:  Weak random string generator algorithm challenge for RC3CTF
 *
 *        Version:  1.0
 *        Created:  10/27/2015 02:08:20
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  Jaime Geiger (@jgeigerm), jmg2967@rit.edu
 *
 * =====================================================================================
 */
#define LEN 32

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <fcntl.h>

char *generate(){
    char *pass = malloc(LEN+1);
    srand(1337);
    for (int i=0; i<LEN; ++i){
        pass[i] = (rand() % (127-33)) + 33;
    }
    pass[LEN] = 0;
    return pass;
}

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
    char buf[60];
    printf("This is the flag access system.\nEnter one time password for entry: ");
    fflush(0);
    fgets(buf, 59, stdin);
    char *pass = generate();
#ifdef DEBUG
    printf("PASS: %s\n", pass);
#endif
    if (!strncmp(pass, buf, LEN)){
        printf("Access granted! Here's the flag: ");
        print_flag();
    } else {
        puts("Access denied... now go away!");
    }
    fflush(0);
    free(pass);
    return 0;
}
