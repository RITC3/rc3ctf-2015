Final Jeopardy
==============
You need to win this jeopardy game despite all odds! You only have $100 and you are against IBM Watson and Stephen Hawking who both have over $100000.<br>
The vulnerability here is an integer overflow. When you get the question wrong you get whatever your wager was subtracted from your current amount of money. If you can overflow the wager and make it negative then you can get LOTS of moneys!!<br>
Your wager is a long and is casted to an integer for the comparison against your current amount of money but only in the positive direction. If it is a negative integer but a positive longer then the wager bounds check will pass and the program will proceed. Then negative integer will be subtracted from your current moneys and you will win!!!
