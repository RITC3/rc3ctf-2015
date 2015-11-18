Bridge of Death (BoD) - 400 Points
==================================
You can do this the long way or the short way. This challenge has 3 stages, you can just skip the first two though.<br>
1. You just need to enter 15 characters and you can move on. (cmp rsi, 16 @ 0x1d16)<br>
2. Enter ToSeekTheHolyGrail. Decoding algorithm starts @ 0x1d21. It is just ROT1<br>
3. Enter the flag! The flag is encoded using the algorithm in genkey.py. This is actually the only required step because debuggers. Encoding algorithm starts at 0x1d5a. Basically an accumulator is anded with 0xaa and the result of that is xored with a byte of the input. Then a bogus byte is added and the value of the non-bogus operation is added to the accumulator (rcx in this case). Reverse that algorithm to get the flag!<br>
Flag: **RC3-MNTY-1072**
