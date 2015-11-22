Mystery - 400 Points
====================
So initially you are told to find someone and then given "ciphertext". But this cipher text is just ascii characters of a message but in octal, because people always use binary and hex. Octal needs love too! But after decoding the message, it should say this:

You must find: the guy in charge of running this CTF. And help him fill out his job application.

This was pretty vague, but at least you could start with finding out who that is. One guess is that it's bigshebang cuz he was in the IRC most of the weekend answering lots of questions. Or you could go to rc3.club/about and find out at the bottom that there is an RC3 E-board position of "Competition Architect" and his name is Luke Matarazzo.

After finding out who ran the CTF, you could find his LinkedIn page and view some of it even without being connected to him. And there's really not much stuff on the page to look at which narrows down what could be important. If you look at his certifications, the license numbers look kinda weird. The Network+ one even has what looks like an IP address. Hmm wonder if it could be related to the challenge? Well, it is. If you go to that IP address in your browser you just get a page that says

umad?

So, if you scan all the tcp ports on the box you'll find an open port running HTTPS on port 56299. When you visit that you get the typical self signed cert warning, then you find a picture called flag.jpg. There's no flag in it, but you should download that. So at this point you should check out the certificate given and realize that it's issued to a different IP address. That's pretty weird. So if you visit that IP address you find another web server running! But the directory is forbidden and you can't see anything:( You could run dir buster to find something, but that probably won't help. Instead, if you open the flag.jpg picture from before, it actually has a nice hint at the bottom of the file! But you have to open it and view the raw data/text of it to see that. It says this at the bottom:

i think /this-is-a-cool-dir/ would be a cool place to visit

So if you go to this directory you'll find EMPLOYMENTAPPLICATION.exe. So you should download this. And if you run the file command on it, it will tell you that it's actually a PDF. So if you open it as a PDF you now have the job application you're looking for! But that's not good enough, that would be too easy. So the flag is hidden in the document as white text on white background and it's base64 encoded so you can't just strings | grep RC3. The text in question is on page 4 and looks like this:

UkMzLTQ5NjAzODAtS0pOR0tOSwo=

If you base64 decode that, you get the flag and you win.

**RC3-4960380-KJNGKNK**
