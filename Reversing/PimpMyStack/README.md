PimpMyStack - 500 Points
=======================
This is actually the only pwn challenge. But it's a good one. It's a relative stack overwrite. You overwrite the pointer of the buffer that is being read into so it writes elsewhere. Make it write on top of the return addr. Then throw your shellcode right after it and there you go! To make this a little harder I added a random canary right before return. To make this even more difficult I could have just turned off execstack and made you ROP, but this is a beginner CTF :). Check out exploit.py for the full exploit details.<br>
Flag: **RC3-WEDIDITREDDIT-89247598374**
