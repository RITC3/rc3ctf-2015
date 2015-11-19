Negotiations - 100 points
=========================
This is a really shitty service that utilizes openssl. As soon as you connect and complete the ssl handshake it disconnects you! Modern browsers won't even connect to it because it uses SSLv3 which is no longer supported. The way to beat the challenge is to probe it with <em>openssl s_client -host [ip] -port [port]</em>. The flag is part of the certificate information that is returned.<br>
Flag: **RC3-SSLV-3909**
