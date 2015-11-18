/*
 * =====================================================================================
 *
 *       Filename:  gnireenigne_101.c
 *
 *    Description:  Not just about strings
 *
 *        Version:  1.0
 *        Created:  11/18/2015 01:31:34
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  Jaime Geiger (@jgeigerm), jmg2967@rit.edu
 *
 * =====================================================================================
 */

#include <stdio.h>
#include <string.h>
char *RC3_STRINGS_101010101 = "sekret_key_d00d";

int main(int argc, char *argv[])
{
    char buf[100];
    printf("Wat is the super sekret key: ");
    fgets(buf, 99, stdin);
    if (!strncmp(RC3_STRINGS_101010101, buf, strlen(RC3_STRINGS_101010101))){
        puts("Nice reversing skillz d00d");
    }else{
        puts("U suk");
    }
    return 0;
}
