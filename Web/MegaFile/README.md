Mega File - 500 Points
======================
There are a few parts to this challenge, but the goal is to get the files that Hillary Clinton has uploaded to the site. The catch is that they may be encrypted, and it turns out when you do get the file it says it is encrypted and encoded. These are the contents of the file shared on Hillary's account:

This secret message is for the president. This encoding and RSA encryption should help keep it safe.

w8KqOpNKNlc+5nUDi1VF5nj9Yo+9J87zvjYbu8DabzDCeWXtt6NKMbWOqTkf/fmy/AVmS5xN9zic
/YrO4rpwia+KEQJJJNG5mFQ7emcddEaEJwMKLTbv1ol//WsUht/JCDMSP1StFLguTY715RgbkThi
BHPNbdgPmY3R23dfjz4=

The encoding is base64 and obviously RSA encryption was used to encrypt the message. Sto get the original plain text you base64 decode the blob at the bottom of the file, then decrypt it using Hillary's RSA private key upon finding it. 

So the first step of the challenge is to get Hillary Clinton's encrypted file and then somehow decrypt it. The front page of the site says on it that there is a public key management system in beta that is available to premium members, which is supposed to hint that Hillary Clinton has uploaded her private key to the site and you have to find it and use that to decrypt the message.

Finding the key or getting her file can happen in any order really. First I'll cover how to get Hillary's private key. On the settings.php page, there is an option to upload an XML file to update a user's first name, last name or bio. This upload is vulnerable to XXE injection, so using this vulnerability it is possible to achieve local file inclusion (LFI). Here is an example XML file that would get /etc/passwd by exploiting the XXE injection vulnerability:

<?xml version="1.0" ?>
<!DOCTYPE passwd [
  <!ELEMENT passwd ANY>
  <!ENTITY passwd SYSTEM "file:///etc/passwd">
]>
<item xml:id="bio">&passwd;</item>

With this it is possible to acquire the source code for the web app. However it isn't possible to directly include php or HTML files via the XXE injection vulnerability because the parser tries to parse it as XML and the tags screw it up and the data is never printed out. So you have to poke around. If you visit a 404 page or check the headers of the website, it will tell you that it's using Apache on Ubuntu. Knowing this would also let you know that the default location for the Apache config would be /etc/apache2/apache2.conf. After getting this file, an interesting configuration is that there is a password protected directory called "backups". The config tells you that /etc/apache2/htpasswd holds the username and hash to access this directory. After learning this you can run a dictionary attack with the rockyou wordlist and find out that the password is "SEXYLOVE". To run this attack with john the ripper you would need the following command:

john htpasswd --wordlist=rockyou.txt

After getting this you can browse to /backups on the website and enter the username and password recovered and you should then see a file called backup.tar.gz. If you download this and extract the files inside it, it has all of the source for the web app. After digging through the source (isn't php lovely?) you should notice a keys.php file that is never referenced in the website. Also this file seems to get some data from the database and then print out a row called 'privkey', so if you visit this page the private key of Hillary Clinton should print out. But that's too simple, so if you visit the page regular you will get redirected. In order to actually get to the page successuflly, you need to be logged in, have a special X-Forwarded-For header, and give a password as the passcode POST parameter. It is easiest to do this with a proxy like BurpSuite. The header you need to add is below:

X-Forwarded-For: for=0.0.0.0; proto=http; by=253.254.255.256

It may not be obvious that there is a password check, but when making the call to the getKeys() function, it takes the POST parameter 'passcode'. The getKeys() function then calls a function called checkpw() and will only do the MySQL query to get the keys if this function returns true. The checkpw basically just has one if statement but with 4 conditions that must all be true in order for this function to return true. The first condition is !!$code. This just means that $code can't be empty. The next one ($code & 1) does a binary and with 1, so if the code is an odd number this condition will be true. The third condition checks to see if the last character is even and if it is, it will be a true condition. This is kind of a contradiction. How can a number be odd but the last character is even? That's impossible. So let's take a string like this: "123tree". This will pass the check because of how letters are translated to numbers. The binary and will only take the numbers and do an and with those. The last character 'e' when converted to a number becomes 0. 0 is considered even so any string that begins with an odd number but has a letter at the end will pass the first three checks. The last check doesn't actually do anything because it always evaluates to true. When doing boolean comparisons in programming languages (except for bash and maybe some others) you need a double equals (==). With a single = it assigns the value to the variable and then checks if the variable itself is true. So $code = "DirtyHarry99" will be true no matter what but if this string was given as the passcode it would not pass all of the other 3 checks.

So now that we know how the checkpw() function works and what we need to make the header, if we correctly add those things to a POST request to keys.php it will spit out the private key of Hillary Clinton. As the page suggests, you have to be careful with formatting and you should copy the key when viewing source or inspecting element so the formatting of the key is correct and there are multiple lines of text rather than just one when it is interpreted as HTML.

So now you just need Hillary Clinton's file and we will be able to decrypt it! you can read the source of the web app and figure out where the vulnerability is or you can tinker around with POST methods and the parameters. The way to get anyone's files can be done a couple ways. When on the home page as a logged in user, there will be a drop down with ids of people who have shared their files with you. However, if you click the submit button and use a proxy to edit the request you can put whatever id you want in there and it will show you the files for that user. The web app doesn't properly check if an account's files have been shared with you when you request them. The other possibility is to just click a file that you have legitimate access to download it, but intercept the request with a proxy. Then you can edit the id given as a POST parameter. This id corresponds directly to the id of the file in the database and has no check if the file belongs to the requesting user or an account that is shared with him.

The file is called "for POTUS.txt" and the contents were shown earlier in this writeup. All you have to do is base64 decode the blob at the bottom of the file and then decrypt the file using the RSA private key obtained from keys.php. The command for decrypting on the Linux command line is this: cat "for POTUS.txt" | openssl rsautl -decrypt -inkey privkey. This will print out the secret message of this:

This password is for the president's eyes only.
RC3-TOO-LEGIT-BRO-142780944

Flag: **RC3-TOO-LEGIT-BRO-142780944**
