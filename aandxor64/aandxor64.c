/*
 * =====================================================================================
 *
 *       Filename:  andxor.c
 *
 *    Description:  Simple xor/and challenge for RC3 CTF
 *
 *        Version:  1.0
 *        Created:  11/16/2015 16:36:03
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  Jaime Geiger (@jgeigerm), jmg2967@rit.edu
 *
 * =====================================================================================
 */
#include <stdio.h>
#include <stdlib.h>

int main(int argc, char *argv[])
{
    int dad[] = {82, 66, 49, 46, 92, 74, 84, 67, 37, 57, 59, 59, 61, 0};
    char dad2[14] = {0};
    for (int i = 0; i<=13; ++i){
        dad2[i] = dad[i] ^ (i&0x12);
    }
    printf("%s\n", dad2);
    return 0;
}
