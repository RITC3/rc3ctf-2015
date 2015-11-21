Diagnostics - 300 Points
========================
This challenge has two diagnostics interfaces reminiscent of ones on a home router, which have very similar vulnerabilities. One will ping a given IP address or hostname and the other will perform a forward dns lookup only. The goal is for you to achieve OS command injection on the first site (ping) and cat a file called creds. This username:password combination will give you access to the second site that does dns lookups. The goal on the second site is to achieve command injection as well, except this time it's a little harder than before.

The site is written in php the first site has this command which runs the command:

proc\_open('ping -c 1 \'' . $\_POST['ping'] . "'", $descriptorspec, $pipes, $cwd);

So if a user gives the input "google.com" (not with double quotes), the system call becomes "ping -c 1 'google.com'" (not with double quotes). So the user input will be inside single quotes (') no matter what, almost like a SQL injection. So one correct way to achieve command injection is with this string: "google.com'; ls #". This turns into the system call of "ping -c 1 'google.com'; ls #'". The first single quote ends the initial quote, the semicolon allows us to run any other command we want. The # symbol comments out the single quote at the end of the system call, and this part is very important. Usually there is an error about unmatching/unterminated quotes when there is an extra single quote hanging around somewhere. It was also possible to achieve command execution using back ticks (`) to execute commands in a subshell. But this limited your output to be one line because the system call was trying to ping whatever the subshell returned. So if you gave this as input: "'`ls`'" it would have tried to ping the output of the ls command, so the error message would only say '<output>' host not found and not show any other lines of output. Another possible solution that gives more than one line of output and still get command execution would be "google.com' && ls #".

After getting command execution, the logical thing to do is run ls, which shows there is a file called creds. Catting that will give you this content:

admin:SuperDuperSecretPa$$w0rd55

This is the username and password combo that will allow you to get access the second part of the challenge which is on a different server. When you get here there is a page that says it will resolve hostnames only. This is supposed to hint that there is some validation of the input. The first part of validation is client side and is completely meaningless. A javascript function checks for weird characters that help to achieve command injection, and the input field has a mexlength of 16 to limit input. To bypass these things you can just turn off javascript and edit the html or use a proxy like Burpsuite and send whatever the heck you want. But when the data gets to the server, it makes sure that the strings starts with a somewhat valid domain name, like a.com or a.co or even a.a. Besides that, the line of php code for the system call with the user input is exactly the same as the first part. So you can take the previous working strings and just add a somewhat valid domain name to the beginning, like this:

google.com'; ls #
a.a' && ls #

And of course it's possible to use sub shells with back ticks (`) or $(...), but then you have to deal with that silly one line output again. And who likes that? But anyway, after achieving command execution, you do ls and see that there aren't any interesting files. But if you do ls -a to show hidden files, there is a file called .flag which has the flag in it. So catting that file will give you the flag.

Some other things about this challenge are that the output was being checked for having semi colons or "<?php", then nothing is printed to the page because I'm paranoid about people having my code for a challenge where they can get code execution. Also, the input had a single & stripped from the input but allowed a double & (&&). This was an attempt to prevent most forms of fork bombs but still allow the && (or do if true in bash) to achieve command execution without a semicolon. Also the > symbol was stripped to disallow most forms of writing files. The www-data user also didn't have write permissions to the /var/www/html directory that the scripts were running from. Didn't want people adding bogus flags or removing the real one or anything like that. Another interesting thing I had done was write all given commands to a log file with the ip that gave the user input. These log files should be in the repo in each html folder.

In order to ensure that competitors couldn't overwrite this file I set the permissions so that www-data could write to the file but not read it, and then I did chattr +a logs, which made it so that data can only ever be added on to the end of the file.

Flag: **RC3-LEETHAX-0059271**
