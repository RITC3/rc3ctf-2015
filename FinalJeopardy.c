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
    long long wager;
    long long long_answer = ((long)rand() << 32) | (long)rand();
    if (long_answer > 10) long_answer+=11;
    puts("Lex Trebeq: And now it's time for Final Jeopardy!");
    puts("            The current scores are: IBM Watson with $102,600, Stephen Hawking with $110,400, and YOU with an astonishing $100.");
    puts("            The category is 'Numbers greater than 10'");
    printf("Enter your answer: ");
    scanf("%lld", &number);
    printf("Enter your wager: ");
    scanf("%lld", &wager);
    if ((int)wager >= 100 || wager < 0){
        puts("You can't even bet that much money...");
        return 0;
    }
    //sleep(5);
    puts("And the answers are in!");
    if (number == long_answer){
        puts("Wow good job you weren't supposed to be able to do that!!");
        moneys += wager;
    } else {
        moneys -= wager;
    }
    if (moneys <= 110400){
        printf("You only have $%d. You lose to Stephen Hawking with $110,400\n", moneys);
    } else {
        printf("Moneys: %d\nWager (int): %d\n", moneys, (int)wager);
        puts("WHAT HOW DOES THAT WORK?? YOU WIN CHARLIE!");
        print_flag();
    }
    return 0;
}


