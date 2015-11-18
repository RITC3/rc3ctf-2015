Password Generator
==================
This challenge relies on the fact that similar OSs have the same PRNGs and different OSs have different ones.<br>
The provided binary was compiled on Debian, which is not the system it runs on for the challenge. The idea is to reverse engineer the binary and see that it is using a static seed to generate a 32 character "random" password. An nmap scan will show that the system running the challenge is freebsd. From there the challenge needs to be recreated on freebsd (seeding off of 1337 and generating a 32 character string) and then the password is found.
