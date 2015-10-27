/*
 * =====================================================================================
 *
 *       Filename:  FinalJeopardy.c
 *
 *    Description:  RC3 CTF 2015 integer overflow challenge
 *
 *        Version:  1.0
 *        Created:  10/12/2015 09:58:04
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  Jaime Geiger (@jgeigerm), jmg2967@rit.edu
 *
 * =====================================================================================
 */

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <time.h>
#include <fcntl.h>

void dot_sleep(int sec){
    for (int i=0;i<=sec;++i){
        sleep(1);
        printf(".");
        fflush(stdout);
    }
    printf(" ");
}

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
    long long number;
    int moneys = 100;
    long wager;
    long long long_answer = ((long)rand() << 32) | (long)rand();
    if (long_answer > 10) long_answer+=11;
    puts("Lex Trebeq: And now it's time for Final Jeopardy!");
    sleep(3);
    puts("            The current scores are: IBM Watson with $102,600, Stephen Hawking with $110,400, and YOU with an astonishing $100.");
    sleep(3);
    puts("            The category is 'Numbers greater than 10'");
    sleep(3);
    printf("Enter your answer: ");
    scanf("%lld", &number);
    printf("Enter your wager: ");
    scanf("%ld", &wager);
    if ((int)wager > 100 || wager < 0){
        puts("You can't even bet that much money...");
        return 0;
    }
    dot_sleep(8);
    puts("And the answers are in!");
    sleep(3);
    if (number == long_answer){
        puts("Wow good job you weren't even supposed to be able to do that!!");
        moneys += wager;
    } else {
        moneys -= wager;
        printf("I'm sorry %lld was not the right answer. It was actually %lld.\n", number, long_answer);
    }
    sleep(3);
    if (moneys <= 110400){
        printf("It looks like despite your answer there was no way for you to win. You lose! (%d)", moneys);
    } else {
        dot_sleep(5);
        printf("Although it looks like through some miracle, you have won with $%d... HOW DOES THAT WORK??\nAnyway you'll be going home with one of our fabulous FLAGS!!!!!\n", moneys);
        sleep(5);
        print_flag();
    }
    return 0;
}
